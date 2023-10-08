import {createRoot} from "react-dom/client";
import ProductCategoryPreview from "../../inc/react/product-category-preview/ProductCategoryPreview";
import productListing from "../../inc/react/product-category-preview/ProductListing";
import SingleProductPreview from "../../inc/react/single-product-preview/SingleProductPreview";

jQuery(document).on("ready", function() {
    (function() {

        const renderedApps = {};
        const whitelistedApps = {};
        let firstStateObject = {};
        let isPushedFirstTime = false;
        let categoriesMap = {};
        let currentComponentId = null;

        let whitelistApp = function(pageId, appId, component) {
            let obj = whitelistedApps[pageId];
            if (!obj) {
                whitelistedApps[pageId] = {};
                obj = whitelistedApps[pageId];
            }
            if (!obj.hasOwnProperty(appId)) {
                obj[appId] = component;
            }
        }

        let scrollToTop = function() {
            document.documentElement.scrollTop = 0;
            document.body.scrollTop = 0;
        };

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

            if (typeof window.initializeFunction === 'function') {
                let returnedObject = window.initializeFunction();
                if (typeof returnedObject === 'object') {
                    for (let key in returnedObject) {
                        if (key === "firstStateObject") {
                            firstStateObject = returnedObject[key];
                        }
                    }
                }
                window.initializeFunction = undefined;
            }
        }

        const productsFactory = (function () {
            const productsLibrary = {};
            const ajaxRequests = {};

            let helpers = {
                findSearchTerm: function (category, subcategory) {
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
                registerProductsInCategory: function (product, category, subcategory) {
                    if (product === null || product === undefined) {
                        return;
                    }

                    if (subcategory === null || subcategory === undefined) {
                        productsLibrary[category.slug] = product;
                    } else {
                        productsLibrary[subcategory.slug] = product;
                    }
                },

                getProductsFromCategory: function (category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);

                    return this.getProductsFromCategorySlug(searchTerm);
                },

                getProductsFromCategorySlug: function (slug) {
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

                getLibrary: function () {
                    return productsLibrary;
                },

                hasProductsInCategoryBySlug: function (slug) {
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
                                success: function (data) {
                                    // Update products for the given category
                                    productsLibrary[slug] = data;
                                    ajaxRequests[slug] = null;
                                    resolve(data.length > 0);
                                },
                                error: function (xhr, status, error) {
                                    ajaxRequests[slug] = null;
                                    reject(error);
                                }
                            });
                            ajaxRequests[slug] = ajax;
                        }
                    });
                },

                hasProductsInCategory: function (category, subcategory) {
                    let searchTerm;
                    if (subcategory === null || subcategory === undefined) {
                        searchTerm = category.slug;
                    } else {
                        searchTerm = subcategory.slug;
                    }

                    return this.hasProductsInCategoryBySlug(searchTerm);
                },

                rejectAllPromises: function (category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);
                    if (ajaxRequests[searchTerm]) {
                        ajaxRequests[searchTerm].abort();
                    }
                },

                areProductsFetched: function (category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);

                    return productsLibrary[searchTerm] !== undefined;
                },

                retrieveProductsSync: function (category, subcategory) {
                    let searchTerm = helpers.findSearchTerm(category, subcategory);
                    return productsLibrary[searchTerm];
                }
            };

            if (react_vars.page === "opened-category") {
                // Register products for current category, subcategory
                if (react_vars.products) {
                    let category = react_vars.category;
                    let subcategory = react_vars.subcategory;
                    methods.registerProductsInCategory(react_vars.products, category, subcategory);
                }
            }

            return methods;
        })();
        window.productsFactory = productsFactory;


        window.renderApp = function(id, component, unmountAll) {
            if (document.getElementById(id)) { //check if element exists before rendering

                if (unmountAll) {
                    for (const pageId in renderedApps) {
                        if (pageId !== id) {
                            console.log(renderedApps[pageId]);
                            renderedApps[pageId].root.unmount();
                            delete renderedApps[pageId].root;
                        }
                    }
                }

                const root = createRoot(document.getElementById(id));
                root.render(component);
                renderedApps[id] = {
                    component: component,
                    root: root
                };
                currentComponentId = id;

                // Scroll to top
                scrollToTop();

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
                    if (renderedApps[appId] === undefined) {
                        continue;
                    }
                    try {
                        renderedApps[appId].root.unmount();
                        delete renderedApps[appId].root;
                    } catch(e) {}
                }
            }
        } );

        let timeout1 = null;
        let timeout2 = null;

        const transitionOverlayLoader = (whenFinishedCallback) => {
            clearTimeout(timeout1);
            clearTimeout(timeout2);
            jQuery(".overlay-loader-container").css('display', 'flex');
            timeout1 = setTimeout(() => {
                if (jQuery("#heading-section").parents("#qodef-page-outer").length > 0) {
                    jQuery("#heading-section").parents("#qodef-page-outer").fadeOut(function () {
                        timeout2 = setTimeout(() => {
                            jQuery(".overlay-loader-container").hide();
                            if (typeof whenFinishedCallback === 'function') {
                                whenFinishedCallback();
                            }
                        }, 500);
                    });
                } else {
                    jQuery(".overlay-loader-container").hide();
                    if (typeof whenFinishedCallback === 'function') {
                        whenFinishedCallback();
                    }
                }
            }, 500);
        };

        const onPopStateHandler=  (e) => {
            if (e.state === null) {
                window.location.reload();
            } else if (e.state.page === "products-page") {
                if (jQuery("#heading-section").parents("#qodef-page-outer").length === 0) {
                    jQuery(".overlay-loader-container").css('display', 'flex');
                    window.location.reload();
                } else {
                    jQuery("#heading-section").parents("#qodef-page-outer").fadeIn();
                }
                // Scroll to top
                scrollToTop();
            } else if (e.state.page === "opened-category") {
                transitionOverlayLoader();
                if (e.state.isSingleProduct) {
                    let props = {...e.state};
                    delete props.page;
                    delete props.isSingleProduct;
                    window.renderApp('single-product-preview', <SingleProductPreview {...props} />);
                    return;
                }

                let props = {...e.state};
                delete props.page;
                console.log("11", props);
                window.renderApp('product-category-preview', <ProductCategoryPreview data={true} {...props} />);

            } else if (e.state.page === "single-product") {
                transitionOverlayLoader();
                let props = {...e.state};
                delete props.page;
                window.renderApp('single-product-preview', <SingleProductPreview {...props} />);
                history.replaceState({
                    page: 'single-product',
                    ...props
                }, null);
            }
        };
        window.addEventListener('popstate', onPopStateHandler);

        // Whitelist all components on other pages
        whitelistApp("opened-category", "product-category-preview",
                <ProductCategoryPreview
                    data={true}
                    category={react_vars.category}
                    subcategory={react_vars.subcategory}
                    subcategories={react_vars.subcategories}
                />
        );

        const product = typeof product_vars !== 'undefined' ? product_vars.product[0] : null;
        whitelistApp("single-product", "single-product-preview",
            <SingleProductPreview product={product} />
        );

        // Initialize all react apps
        initializeApps();

        // Categories & subcategories data
        const CategoriesManager = function() {
            let allCategories = react_top.categories;

            delete react_top.categories;
            return {
                getAllCategories: function() {
                  return allCategories;
                },
                getSubcategoriesForCategoryBySlug: function(slug) {
                    for (let i=0; i<allCategories.length; i++) {
                        if (allCategories[i].slug === slug) {
                            return allCategories[i].subcategories;
                        }
                    }
                }
            };
        };


        window.reactMain = {
            pushStartHistoryState: function() {
                if (isPushedFirstTime || firstStateObject === null) {
                    return;
                }
                history.pushState(firstStateObject, null);
            },

            transitionOverlayLoader: transitionOverlayLoader,

            categoriesManager: CategoriesManager(),

            scrollToTop: scrollToTop,

            openProduct: function(prevProps, product, callback) {
                console.log(product, callback);
                transitionOverlayLoader(() => {
                    if (typeof callback === 'function') {
                        callback();
                    }
                });

                // Push a new state to the history
                const newState = {
                    ...prevProps
                };
                const newTitle = product.post_title + " — Kandelaber";
                const newUrl = '/proizvod/' + product.post_name + "/";

                history.pushState(newState, null, newUrl);
                document.title = newTitle;

                const appsToRemove = whitelistedApps["single-product"];
                for (const appId in appsToRemove) {
                    if (renderedApps[appId] === undefined) {
                        continue;
                    }
                    try {
                        renderedApps[appId].root.unmount();
                    } catch(e) {}
                    delete renderedApps[appId].root;
                }

                history.replaceState({
                    page: 'single-product',
                    product: product
                }, null);
            }
        };

    })();
});