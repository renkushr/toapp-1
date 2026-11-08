<style>
.toapp-lang-switcher {
    position: relative;
    z-index: 30;
}
.toapp-lang-switcher.is-open {
    z-index: 1200;
}
.toapp-lang-switcher__btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    min-height: 36px;
    padding: 6px 10px;
    border: 1px solid rgba(15, 23, 42, .14);
    border-radius: 12px;
    background: #fff;
    color: #0f172a;
    font-size: 13px;
    font-weight: 600;
    line-height: 1;
    cursor: pointer;
}
.toapp-lang-switcher__btn:hover,
.toapp-lang-switcher.is-open .toapp-lang-switcher__btn {
    border-color: #1268f3;
    color: #1268f3;
}
.toapp-lang-switcher__label {
    max-width: 7.5rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.toapp-lang-switcher__caret {
    font-size: 12px;
    opacity: .7;
}
.toapp-lang-switcher__menu {
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    min-width: 220px;
    max-height: min(320px, 70vh);
    overflow: auto;
    padding: 6px;
    border: 1px solid rgba(15, 23, 42, .12);
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 18px 40px rgba(15, 23, 42, .18);
    z-index: 1201;
}
.toapp-lang-switcher__menu.is-portal {
    position: fixed;
    top: 0;
    left: 0;
    right: auto;
    margin: 0;
    z-index: 3000;
}
.toapp-lang-switcher__item {
    display: flex;
    flex-direction: column;
    gap: 2px;
    padding: 10px 12px;
    border-radius: 10px;
    color: #0f172a;
    text-decoration: none;
}
.toapp-lang-switcher__item span {
    font-size: 12px;
    color: #64748b;
}
.toapp-lang-switcher__item:hover,
.toapp-lang-switcher__item.is-active {
    background: rgba(18, 104, 243, .08);
    color: #1268f3;
}
.toapp-lang-switcher--user .toapp-lang-switcher__btn {
    width: 46px;
    height: 46px;
    min-height: 46px;
    min-width: 46px;
    padding: 0;
    justify-content: center;
    border: 0;
    border-radius: 16px;
    background: rgba(255, 255, 255, .78);
    box-shadow: 0 14px 32px rgba(15, 23, 42, .12);
}
.toapp-lang-switcher--user .toapp-lang-switcher__btn:hover,
.toapp-lang-switcher--user.is-open .toapp-lang-switcher__btn {
    border: 0;
    color: #1268f3;
}
.toapp-lang-switcher--portal .toapp-lang-switcher__btn {
    min-height: 40px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .92);
}
.toapp-lang-switcher--compact .toapp-lang-switcher__btn {
    width: 46px;
    height: 46px;
    min-width: 46px;
    min-height: 46px;
    padding: 0;
    justify-content: center;
    border: 0;
    border-radius: 16px;
    background: rgba(255, 255, 255, .78);
    box-shadow: 0 14px 32px rgba(15, 23, 42, .12);
    font-size: 18px;
}
.toapp-lang-switcher--compact .toapp-lang-switcher__btn:hover,
.toapp-lang-switcher--compact.is-open .toapp-lang-switcher__btn {
    border: 0;
    color: #1268f3;
}
.toapp-lang-switcher--compact .toapp-lang-switcher__label {
    display: none;
}
.toapp-lang-switcher--compact .toapp-lang-switcher__caret {
    display: none;
}
.page-header,
.app-topbar {
    position: relative;
    z-index: 40;
}
@media (max-width: 575.98px) {
    .toapp-lang-switcher__menu {
        min-width: min(240px, 80vw);
    }
    .toapp-lang-switcher--compact .toapp-lang-switcher__btn,
    .app-topbar .toapp-lang-switcher--user .toapp-lang-switcher__btn {
        width: 42px;
        height: 42px;
        min-width: 42px;
        min-height: 42px;
        border-radius: 14px;
        font-size: 17px;
    }
}
</style>
<script>
(function () {
    function restoreMenu(root) {
        var menu = root._langMenu || root.querySelector('.toapp-lang-switcher__menu');
        if (!menu) return;
        menu.hidden = true;
        menu.classList.remove('is-portal');
        menu.style.top = '';
        menu.style.left = '';
        menu.style.width = '';
        if (menu.parentElement !== root) {
            root.appendChild(menu);
        }
    }

    function closeAll(except) {
        document.querySelectorAll('[data-lang-switcher].is-open').forEach(function (el) {
            if (except && el === except) return;
            el.classList.remove('is-open');
            restoreMenu(el);
            var btn = el.querySelector('.toapp-lang-switcher__btn');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    }

    function placeMenu(root) {
        var btn = root.querySelector('.toapp-lang-switcher__btn');
        var menu = root._langMenu || root.querySelector('.toapp-lang-switcher__menu');
        if (!btn || !menu) return;

        root._langMenu = menu;
        if (menu.parentElement !== document.body) {
            document.body.appendChild(menu);
        }

        menu.classList.add('is-portal');
        menu.hidden = false;

        var rect = btn.getBoundingClientRect();
        var menuWidth = Math.max(220, Math.round(menu.offsetWidth) || 220);
        var gap = 6;
        var left = rect.right - menuWidth;
        if (left < 8) left = 8;
        if (left + menuWidth > window.innerWidth - 8) {
            left = Math.max(8, window.innerWidth - menuWidth - 8);
        }

        var top = rect.bottom + gap;
        var menuHeight = menu.offsetHeight || 240;
        if (top + menuHeight > window.innerHeight - 8 && rect.top > menuHeight + gap) {
            top = rect.top - menuHeight - gap;
        }

        menu.style.top = Math.round(top) + 'px';
        menu.style.left = Math.round(left) + 'px';
        menu.style.width = Math.round(menuWidth) + 'px';
    }

    document.addEventListener('click', function (event) {
        var inMenu = event.target.closest('.toapp-lang-switcher__menu');
        var root = event.target.closest('[data-lang-switcher]');

        // Clicks inside a portaled menu should not close it (except following a link).
        if (inMenu && !root) {
            return;
        }

        if (!root) {
            closeAll();
            return;
        }

        var btn = event.target.closest('.toapp-lang-switcher__btn');
        if (!btn) return;
        event.preventDefault();
        var willOpen = !root.classList.contains('is-open');
        closeAll();
        if (willOpen) {
            root.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
            placeMenu(root);
        }
    });

    window.addEventListener('resize', function () {
        document.querySelectorAll('[data-lang-switcher].is-open').forEach(placeMenu);
    });
    window.addEventListener('scroll', function () {
        document.querySelectorAll('[data-lang-switcher].is-open').forEach(placeMenu);
    }, true);
})();
</script>
