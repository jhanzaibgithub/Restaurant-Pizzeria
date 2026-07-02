(function () {
    'use strict';

    var nav = document.getElementById('landingNav');
    var toggle = document.querySelector('[data-nav-toggle]');
    var menu = document.querySelector('[data-nav-menu]');
    var cursorGlow = document.getElementById('cursorGlow');

    function updateNav() {
        if (!nav) {
            return;
        }

        nav.classList.toggle('is-scrolled', window.scrollY > 18);
    }

    updateNav();
    window.addEventListener('scroll', updateNav, { passive: true });

    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            var isOpen = menu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            toggle.querySelector('i').className = isOpen ? 'mdi mdi-close' : 'mdi mdi-menu';
        });

        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.querySelector('i').className = 'mdi mdi-menu';
            });
        });
    }

    if (cursorGlow) {
        window.addEventListener('pointermove', function (event) {
            cursorGlow.style.left = event.clientX + 'px';
            cursorGlow.style.top = event.clientY + 'px';
        }, { passive: true });
    }

    if (window.AOS) {
        window.AOS.init({
            duration: 760,
            easing: 'ease-out-cubic',
            once: true,
            offset: 70
        });
    }

    if (window.Swiper) {
        new window.Swiper('.preview-swiper', {
            loop: true,
            speed: 850,
            autoplay: {
                delay: 3600,
                disableOnInteraction: false
            },
            pagination: {
                el: '.preview-swiper .swiper-pagination',
                clickable: true
            }
        });

        new window.Swiper('.testimonial-swiper', {
            loop: true,
            speed: 780,
            autoplay: {
                delay: 4200,
                disableOnInteraction: false
            }
        });
    }

    var counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) {
                return;
            }

            var node = entry.target;
            var target = parseInt(node.getAttribute('data-counter'), 10) || 0;
            var duration = 1500;
            var start = performance.now();

            function tick(now) {
                var progress = Math.min((now - start) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                node.textContent = Math.floor(target * eased).toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(tick);
                }
            }

            requestAnimationFrame(tick);
            counterObserver.unobserve(node);
        });
    }, { threshold: .35 });

    document.querySelectorAll('[data-counter]').forEach(function (counter) {
        counterObserver.observe(counter);
    });

    document.querySelectorAll('.faq-item button').forEach(function (button) {
        button.addEventListener('click', function () {
            var answer = button.nextElementSibling;
            var isOpen = button.getAttribute('aria-expanded') === 'true';

            document.querySelectorAll('.faq-item button').forEach(function (otherButton) {
                var otherAnswer = otherButton.nextElementSibling;
                otherButton.setAttribute('aria-expanded', 'false');
                otherAnswer.style.maxHeight = null;
            });

            if (!isOpen) {
                button.setAttribute('aria-expanded', 'true');
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        });
    });

    if (window.gsap && window.ScrollTrigger) {
        window.gsap.registerPlugin(window.ScrollTrigger);

        window.gsap.to('.chart-card span', {
            scaleY: .72,
            transformOrigin: 'bottom',
            stagger: .07,
            repeat: -1,
            yoyo: true,
            duration: 1.2,
            ease: 'sine.inOut'
        });

        window.gsap.utils.toArray('.tilt-card').forEach(function (card) {
            card.addEventListener('pointermove', function (event) {
                var rect = card.getBoundingClientRect();
                var x = (event.clientX - rect.left) / rect.width - .5;
                var y = (event.clientY - rect.top) / rect.height - .5;
                window.gsap.to(card, {
                    rotateY: x * 5,
                    rotateX: y * -5,
                    duration: .25,
                    ease: 'power2.out'
                });
            });

            card.addEventListener('pointerleave', function () {
                window.gsap.to(card, {
                    rotateY: 0,
                    rotateX: 0,
                    duration: .35,
                    ease: 'power2.out'
                });
            });
        });

        window.gsap.to('.gradient-text', {
            backgroundPosition: '200% center',
            duration: 4,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut'
        });
    }
})();
