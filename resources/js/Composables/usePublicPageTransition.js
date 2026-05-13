import { router } from '@inertiajs/vue3';
import { nextTick, onMounted } from 'vue';

const publicRouteOrder = ['/inicio', '/sobre_nos', '/cultos', '/eventos', '/contato'];
const transitionStorageKey = 'familia_resgate_public_transition_direction';

function normalizePublicPath(path) {
    if (!path || path === '/') {
        return '/inicio';
    }

    return path.split('?')[0].replace(/\/$/, '') || '/inicio';
}

function getPublicRouteDirection(fromPath, toPath) {
    const fromIndex = publicRouteOrder.indexOf(normalizePublicPath(fromPath));
    const toIndex = publicRouteOrder.indexOf(normalizePublicPath(toPath));

    if (fromIndex === -1 || toIndex === -1 || fromIndex === toIndex) {
        return 'forward';
    }

    return toIndex > fromIndex ? 'forward' : 'backward';
}

function readPendingDirection() {
    if (typeof window === 'undefined') {
        return 'forward';
    }

    const direction = window.sessionStorage.getItem(transitionStorageKey);
    window.sessionStorage.removeItem(transitionStorageKey);

    return direction === 'backward' ? 'backward' : 'forward';
}

export function usePublicPageTransition(pageElement) {
    function animatePageEntry() {
        onMounted(async () => {
            await nextTick();

            const element = pageElement.value;

            if (!element) {
                return;
            }

            element.classList.add(`public-page-enter-${readPendingDirection()}`);

            window.setTimeout(() => {
                element.classList.remove('public-page-enter-forward', 'public-page-enter-backward');
            }, 720);
        });
    }

    function navigatePublicPage(event, targetPath) {
        if (event) {
            event.preventDefault();
        }

        if (typeof window === 'undefined') {
            router.visit(targetPath, { preserveScroll: false });
            return;
        }

        const currentPath = normalizePublicPath(window.location.pathname);
        const nextPath = normalizePublicPath(targetPath);

        if (currentPath === nextPath) {
            return;
        }

        const direction = getPublicRouteDirection(currentPath, nextPath);
        const exitClass = direction === 'forward' ? 'public-page-exit-forward' : 'public-page-exit-backward';
        const element = pageElement.value;

        window.sessionStorage.setItem(transitionStorageKey, direction);

        if (element) {
            element.classList.remove('public-page-exit-forward', 'public-page-exit-backward');
            element.classList.add(exitClass);
        }

        window.setTimeout(() => {
            router.visit(targetPath, { preserveScroll: false });
        }, 260);
    }

    animatePageEntry();

    return {
        navigatePublicPage,
        publicRouteOrder,
    };
}
