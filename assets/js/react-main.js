import {createRoot} from "react-dom/client";
import ProductCategoryPreview from "../../inc/react/product-category-preview/ProductCategoryPreview";

jQuery(document).on("ready", function() {
    (function() {

        const renderedApps = {};
        const whitelistedApps = {};

        let whitelistApp = function(pageId, appId, component) {
            let obj = whitelistedApps[pageId];
            if (!obj) {
                whitelistedApps[pageId] = {};
                obj = whitelistedApps[pageId];
            }
            if (!obj.hasOwnProperty('appId')) {
                obj[appId] = component;
            }
        }

        let initializeApps = function() {
            if (typeof window.currentPage === typeof undefined) {
                return;
            }
            let appsToRender = whitelistedApps[window.currentPage];
            if (!appsToRender) {
                return;
            }

            for (const appId in appsToRender) {
                window.renderApp(appId, appsToRender[appId]);
            }
        }

        window.renderApp = function(id, component) {
            if (document.getElementById(id)) { //check if element exists before rendering
                const root = createRoot(document.getElementById(id));
                root.render(component);
                renderedApps[id] = {
                    component: component,
                    root: root
                };
            } else {
                console.warn("No element present with id '" + id + "'");
            }
        }

        window.addEventListener( 'popstate', function(e) {
            for (const pageId in whitelistedApps) {
                if (pageId === e.state.page) {
                    continue;
                }

                const appsToRemove = whitelistedApps[pageId];

                for (const appId in appsToRemove) {
                    renderedApps[appId].root.unmount();
                    delete renderedApps[appId];
                }
            }
        } );

        // Whitelist all components on other pages
        whitelistApp("opened-category", "product-category-preview",
            <ProductCategoryPreview
                category={react_vars.category}
                subcategory={react_vars.subcategory}
                subcategories={react_vars.subcategories}
            />
        );

        // Initialize all react apps
        initializeApps();

    })();
});