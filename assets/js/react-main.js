import {createRoot} from "react-dom/client";
import ProductCategoryPreview from "../../inc/react/product-category-preview/ProductCategoryPreview";
import productListing from "../../inc/react/product-category-preview/ProductListing";

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

        const productsFactory = (function() {
            const productsLibrary = {};
            const ajaxRequests = {};

            let helpers = {
                findSearchTerm: function(category, subcategory) {
                    let searchTerm;
                    if (subcategory === null || subcategory === undefined) {
                        searchTerm = category.slug;
                    } else {
                        searchTerm = subcategory.slug;
                    }

                    return searchTerm;
                }
            };

            let methods = {
                registerProductsInCategory: function(product, category, subcategory) {
                    if (product === null || product === undefined) {
                        return;
                    }

                    if (subcategory === null || subcategory === undefined) {
                        productsLibrary[category.slug] = product;
                    } else {
                        productsLibrary[subcategory.slug] = product;
                    }
                },

                getProductsFromCategory: function(category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);

                    return this.getProductsFromCategorySlug(searchTerm);
                },

                getProductsFromCategorySlug: function(slug) {
                    return new Promise((resolveOuter, rejectOuter) => {
                        this.hasProductsInCategoryBySlug(slug)
                            .then((hasProducts) => {
                                resolveOuter(productsLibrary[slug]);
                            })
                            .catch((error) => {
                                rejectOuter(error);
                            })
                    });
                },

                getLibrary: function() {
                    return productsLibrary;
                },

                hasProductsInCategoryBySlug: function(slug) {
                    let isFetched = productsLibrary[slug] !== undefined;

                    return new Promise((resolve, reject) => {
                        if (isFetched) {
                            resolve(productsLibrary[slug].length > 0);
                            return;
                        } else {
                            let ajax = jQuery.ajax({
                                url: react_vars.ajax_url,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'fetch_products_for_category',
                                    slug: slug,
                                },
                                success: function(data) {
                                    // Update products for the given category
                                    productsLibrary[slug] = data;
                                    ajaxRequests[slug] = null;
                                    resolve(data.length > 0);
                                },
                                error: function(xhr, status, error) {
                                    ajaxRequests[slug] = null;
                                    reject(error);
                                }
                            });
                            ajaxRequests[slug] = ajax;
                        }
                    });
                },

                hasProductsInCategory: function(category, subcategory) {
                    let searchTerm;
                    if (subcategory === null || subcategory === undefined) {
                        searchTerm = category.slug;
                    } else {
                        searchTerm = subcategory.slug;
                    }

                    return this.hasProductsInCategoryBySlug(searchTerm);
                },

                rejectAllPromises: function(category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);
                    if (ajaxRequests[searchTerm]) {
                        ajaxRequests[searchTerm].abort();
                    }
                },

                areProductsFetched: function(category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);

                    return productsLibrary[searchTerm] !== undefined;
                },

                retrieveProductsSync: function(category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);
                    return productsLibrary[searchTerm];
                }
            };

            // Register products for current category, subcategory
            if (react_vars.products) {
                let category = react_vars.category;
                let subcategory = react_vars.subcategory;
                methods.registerProductsInCategory(react_vars.products, category, subcategory);
            }

            return methods;
        })();
        window.productsFactory = productsFactory;

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