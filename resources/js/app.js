import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Smooth PJAX navigation with directional slide transitions for top navbar links.
 */
(function initEventraPjax() {
    const MAIN_SELECTOR = '#eventra-main';
    const NAV_LINK_SELECTOR = 'header nav a[data-pjax]';
    const PROGRESS_SELECTOR = '#eventra-progress';

    let activeController = null;
    let isTransitioning = false;

    const getNavLinks = () => Array.from(document.querySelectorAll(NAV_LINK_SELECTOR));

    const normalizePath = (urlString) => {
        try {
            const parsed = new URL(urlString, window.location.origin);
            return parsed.pathname.replace(/\/+$/, '') || '/';
        } catch {
            return '/';
        }
    };

    const getNavIndex = (urlString) => {
        const targetPath = normalizePath(urlString);
        const links = getNavLinks();
        return links.findIndex((link) => normalizePath(link.href) === targetPath);
    };

    const updateActiveNavLinks = (urlString) => {
        const targetPath = normalizePath(urlString);
        getNavLinks().forEach((link) => {
            if (link.hasAttribute('data-pjax-brand')) {
                return;
            }
            const isActive = normalizePath(link.href) === targetPath;
            link.classList.toggle('text-[#23238E]', isActive);
            link.classList.toggle('font-semibold', isActive);
            link.classList.toggle('text-[#646B8A]', !isActive);
        });
    };

    const setProgress = (percent, visible) => {
        const bar = document.querySelector(PROGRESS_SELECTOR);
        if (!bar) {
            return;
        }
        bar.style.width = `${percent}%`;
        bar.style.opacity = visible ? '1' : '0';
    };

    const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

    const navigate = async (url, { pushHistory = true, direction = null } = {}) => {
        const mainEl = document.querySelector(MAIN_SELECTOR);
        if (!mainEl) {
            window.location.href = url;
            return;
        }

        if (normalizePath(url) === normalizePath(window.location.href) && new URL(url, window.location.origin).search === window.location.search) {
            return;
        }

        if (activeController) {
            activeController.abort();
        }
        activeController = new AbortController();

        const currentIndex = getNavIndex(window.location.href);
        const targetIndex = getNavIndex(url);
        const slideDirection = direction ?? (
            currentIndex !== -1 && targetIndex !== -1 && targetIndex < currentIndex ? 'right' : 'left'
        );

        isTransitioning = true;
        setProgress(35, true);

        try {
            const exitClass = slideDirection === 'left' ? 'page-exit-left' : 'page-exit-right';
            const enterClass = slideDirection === 'left' ? 'page-enter-from-right' : 'page-enter-from-left';

            const fetchPromise = fetch(url, {
                signal: activeController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            });

            mainEl.classList.add('page-stage', exitClass);
            const [response] = await Promise.all([fetchPromise, wait(150)]);
            setProgress(75, true);

            if (!response.ok) {
                window.location.href = url;
                return;
            }

            const html = await response.text();
            const parsedDoc = new DOMParser().parseFromString(html, 'text/html');
            const nextMain = parsedDoc.querySelector(MAIN_SELECTOR);

            if (!nextMain) {
                window.location.href = response.url || url;
                return;
            }

            const finalUrl = response.url || url;

            if (pushHistory) {
                window.history.pushState({ url: finalUrl }, '', finalUrl);
            }

            document.title = parsedDoc.title || document.title;
            updateActiveNavLinks(finalUrl);

            mainEl.classList.remove(exitClass);
            mainEl.classList.add(enterClass);
            mainEl.innerHTML = nextMain.innerHTML;

            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                window.Alpine.initTree(mainEl);
            }

            window.scrollTo({ top: 0, behavior: 'instant' });

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    mainEl.classList.remove(enterClass);
                });
            });

            setProgress(100, true);
            await wait(220);
            setProgress(0, false);
        } catch (err) {
            if (err.name !== 'AbortError') {
                window.location.href = url;
            }
        } finally {
            isTransitioning = false;
        }
    };

    document.addEventListener('click', (event) => {
        const link = event.target.closest(NAV_LINK_SELECTOR);
        if (!link) {
            return;
        }

        if (
            event.defaultPrevented ||
            event.metaKey ||
            event.ctrlKey ||
            event.shiftKey ||
            event.altKey ||
            event.button !== 0 ||
            link.target === '_blank' ||
            link.hasAttribute('download')
        ) {
            return;
        }

        const targetUrl = new URL(link.href, window.location.origin);
        if (targetUrl.origin !== window.location.origin) {
            return;
        }

        event.preventDefault();
        if (!isTransitioning) {
            navigate(targetUrl.href, { pushHistory: true });
        }
    });

    window.addEventListener('popstate', () => {
        if (document.querySelector(MAIN_SELECTOR)) {
            navigate(window.location.href, { pushHistory: false, direction: 'right' });
        }
    });
})();
