<?php

namespace App\CentralLogics;


use App\Model\Product;
use App\Model\Review;
use App\Model\Wishlist;

class ProductLogic
{
    public static function get_product($id)
    {
        return Product::active()->with(['rating', 'branch_product'])->where('id', $id)->first();
    }	

        public static function get_latest_products($branch, $limit, $offset, $product_type, $name, $category_ids)
        {
            // Set default limit and offset if they are null
            $limit = is_null($limit) ? 10 : (int)$limit;
            $offset = is_null($offset) ? 1 : (int)$offset;

            $key = explode(' ', $name);
            
            // Build the query
            $paginator = Product::active()->
                with(['branch_products' => function ($q) use ($branch) {
                    
                    if ($branch) {
                        $q->where(['is_available' => 1, 'branch_id' => $branch->id]);
                    } else {
                        // If no branch is provided, fetch all products with is_available = 1
                        $q->where('is_available', 1);
                    }
                }])
                ->whereHas('branch_product.branch', function ($query) {
                    $query->where('status', 1); // Ensure the branch is active
                })
                ->branchProductAvailability()  // Assuming this is a scope to filter product availability
                ->where(function ($q) use ($key) {
                    // Filter products by name using keywords from $name
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                })
                ->when(isset($product_type) && ($product_type == 'veg' || $product_type == 'non_veg'), function ($query) use ($product_type) {
                    // Filter by product type (veg or non_veg)
                    return $query->productType(($product_type == 'veg') ? 'veg' : 'non_veg');
                })
                ->when(isset($category_ids), function ($query) use ($category_ids) {
                    
                    // Filter by category_ids if provided
                    return $query->whereJsonContains('category_ids', ['id' => $category_ids]);
                })
                // Check if branch is provided and filter products by branch_id in the products table
                ->when($branch, function ($query) use ($branch) {
                    return $query->where('branch_id', $branch->id);
                })
                ->with(['rating','translations','kitchen.printer'])  // Include product ratings
                ->latest()  
                ->paginate($limit, ['*'], 'page', $offset);  

            // Check if translations are present in the result
            $products = $paginator->items();
            foreach ($products as &$product) {
                if (isset($product->tags)) {
                    // Decode the JSON field 'tags'
                    $product->tags = json_decode($product->tags, true);
                }
            }
                
            return [
                'total_size' => $paginator->total(),
                'limit' => $limit,
                'offset' => $offset,
                'products' => $products,
            ];
        }



    public static function get_wishlished_products($limit, $offset, $request)
    {
        $product_ids = Wishlist::where('user_id', $request->user()->id)->get()->pluck('product_id')->toArray();
        $products = Product::active()
            ->with(['rating', 'branch_product'])
            ->whereHas('branch_product.branch', function ($query) {
                $query->where('status', 1);
            })
            ->branchProductAvailability()
            ->whereIn('id', $product_ids)
            ->orderBy("created_at", 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $products->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $products->items()
        ];
    }

    public static function get_popular_products($limit, $offset, $product_type)
    {
        $limit = is_null($limit) ? 10 : $limit;
        $offset = is_null($offset) ? 1 : $offset;

        $paginator = Product::active()
            ->with(['rating', 'branch_product'])
            ->whereHas('branch_product.branch', function ($query) {
                $query->where('status', 1);
            })
            ->branchProductAvailability()
            ->when(isset($product_type) && ($product_type == 'veg' || $product_type == 'non_veg'), function ($query) use ($product_type) {
                return $query->productType(($product_type == 'veg') ? 'veg' : 'non_veg');
            })
            ->orderBy('popularity_count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_related_products($product_id)
    {
        $product = Product::find($product_id);
        return Product::active()
            ->with(['rating', 'branch_product'])
            ->whereHas('branch_product.branch', function ($query) {
                $query->where('status', 1);
            })
            ->branchProductAvailability()
            ->where('category_ids', $product->category_ids)
            ->where('id', '!=', $product->id)
            ->limit(10)
            ->get();
    }

    public static function search_products($name, $limit, $offset, $product_type)
    {
        $limit = is_null($limit) ? 10 : $limit;
        $offset = is_null($offset) ? 1 : $offset;

        if($product_type != 'veg' && $product_type != 'non_veg') {
            $product_type = 'all';
        }

        $key = explode(' ', $name);
        $paginator = Product::active()
            ->with(['rating', 'branch_product'])
            ->whereHas('branch_product.branch', function ($query) {
                $query->where('status', 1);
            })
            ->branchProductAvailability()
            ->when(isset($product_type) && ($product_type != 'all'), function ($query) use ($product_type) {
                return $query->productType(($product_type == 'veg') ? 'veg' : 'non_veg');
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
                $q->orWhereHas('tags',function($query) use ($key){
                    $query->where(function($q) use ($key){
                        foreach ($key as $value) {
                            $q->where('tag', 'like', "%{$value}%");
                        };
                    });
                });
            })
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }


    public static function get_product_review($id)
    {
        $reviews = Review::where('product_id', $id)->get();
        return $reviews;
    }

    public static function get_rating($reviews)
    {
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;
        foreach ($reviews as $key => $review) {
            if ($review->rating == 5) {
                $rating5 += 1;
            }
            if ($review->rating == 4) {
                $rating4 += 1;
            }
            if ($review->rating == 3) {
                $rating3 += 1;
            }
            if ($review->rating == 2) {
                $rating2 += 1;
            }
            if ($review->rating == 1) {
                $rating1 += 1;
            }
        }
        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }

    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }
	
	 protected static function booted()
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
	

}
