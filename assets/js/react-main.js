import {createRoot} from "react-dom/client";
import ProductCategoryPreview from "../../inc/react/product-category-preview/ProductCategoryPreview";
import productListing from "../../inc/react/product-category-preview/ProductListing";
import SingleProductPreview from "../../inc/react/single-product-preview/SingleProductPreview";
import {jsx} from "react/jsx-runtime";

jQuery(document).on("ready", function() {
    (function() {

        const renderedApps = {};
        const whitelistedApps = {};
        let firstStateObject = {};
        let isPushedFirstTime = false;
        let categoriesMap = {};
        let currentComponentId = null;

        const consts = {
            PRODUCT_CATEGORY_PREVIEW: 'product-category-preview',
            SINGLE_PRODUCT_PREVIEW: 'single-product-preview'
        };

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

        const ProductsManager = (function () {
            const productsLibrary = {};
            const ajaxRequests = {};
            const visitedProducts = {};

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

            // Methods for visited products
            let visitedProductsMethods = {
                saveVisitedProduct: (slug, product, recommendations, recommendedProductsCategory) => {
                    visitedProducts[slug] = {
                        product: product,
                        recommended_products: recommendations,
                        recommended_products_category: recommendedProductsCategory
                    };
                },

                isProductAlreadyVisited: (slug) => {
                    return visitedProducts[slug] !== undefined;
                },

                getProduct: function (slug) {
                    return new Promise((resolve, reject) => {
                        if (!this.isProductAlreadyVisited(slug)) {
                            // Do the product fetching
                            let ajax = jQuery.ajax({
                                url: react_vars.ajax_url,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'fetch_product',
                                    slug: slug,
                                },
                                success: function (data) {
                                    if (data.found) {
                                        resolve(data.payload);
                                    } else {
                                        reject(data.error);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    reject(error);
                                }
                            });
                        } else {
                            resolve(visitedProducts[slug]);
                        }
                    });
                },

                get: () => {
                    return visitedProducts;
                }
            }


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
                },

                // Visited products operations
                visitedProducts: visitedProductsMethods
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
        window.productsFactory = ProductsManager;

        const ProductLibrary = (() => {

        })();


        window.renderApp = function(id, component, unmountAll, force) {
            if (document.getElementById(id)) { //check if element exists before rendering

                if (unmountAll) {
                    for (const pageId in renderedApps) {
                        try {
                            if (pageId !== id || typeof force !== 'undefined' && force) {
                                renderedApps[pageId].root.unmount();
                                delete renderedApps[pageId].root;
                            }
                        } catch(e) {}
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

        const transitionOverlayLoader = (whenFinishedCallback, hideOverlay, timeoutIntervals) => {
            clearTimeout(timeout1);
            clearTimeout(timeout2);
            hideOverlay = hideOverlay === undefined ? true : hideOverlay;
            timeoutIntervals = timeoutIntervals === undefined ? { hide: 500, show: 500 } : timeoutIntervals;
            jQuery(".overlay-loader-container").css('display', 'flex');
            timeout1 = setTimeout(() => {
                if (jQuery("#heading-section").parents("#qodef-page-outer").length > 0) {
                    jQuery("#heading-section").parents("#qodef-page-outer").fadeOut(function () {
                        timeout2 = setTimeout(() => {
                            if (hideOverlay) {
                                jQuery(".overlay-loader-container").hide();
                            }
                            if (typeof whenFinishedCallback === 'function') {
                                whenFinishedCallback();
                            }
                        }, timeoutIntervals.show);
                    });
                } else {
                    if (hideOverlay) {
                        jQuery(".overlay-loader-container").hide();
                    }
                    if (typeof whenFinishedCallback === 'function') {
                        whenFinishedCallback();
                    }
                }
            }, timeoutIntervals.hide);
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
                    window.renderApp(consts.SINGLE_PRODUCT_PREVIEW, <SingleProductPreview {...props} />);
                    return;
                }

                let props = {...e.state};
                delete props.page;
                window.renderApp(consts.PRODUCT_CATEGORY_PREVIEW, <ProductCategoryPreview data={true} {...props} />);

            } else if (e.state.page === "single-product") {
                window.showLoader();
                let props = {...e.state};
                delete props.page;
                window.renderApp(consts.SINGLE_PRODUCT_PREVIEW, <SingleProductPreview {...props} />);
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

        let props = null;
        if (typeof product_vars !== 'undefined') {
            props = product_vars;
        }
        whitelistApp("single-product", consts.SINGLE_PRODUCT_PREVIEW,
            <SingleProductPreview {...props} />
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
                },

                getPropsForComponentRendering: function (slug) {
                    for (let i=0; i<allCategories.length; i++) {
                        if (allCategories[i].slug === slug) {
                            return {
                                category: allCategories[i],
                                subcategory: null
                            };
                        } else if (allCategories[i].subcategories && allCategories[i].subcategories.length > 0) {
                            let subcategories = allCategories[i].subcategories;
                            for (let j=0; j<subcategories.length; j++) {
                                if (subcategories[j].slug === slug) {
                                    return {
                                        category: allCategories[i],
                                        subcategory:  subcategories[j]
                                    }
                                }
                            }
                        }
                    }
                },

                getURLPathForCategories: function (categories, start) {
                    if (start === 0) {
                        return "/" + categories[categories.length-1].slug + "/";
                    } else {
                        let url = "/";
                        let count = 0;
                        for (let i = categories.length - start; i >= 0; i--) {
                            if (count > 0) {
                                url += "/";
                            }
                            url += categories[i].slug;
                            count++;
                        }
                        url += "/";
                        return url;
                    }
                },

                getCategoryData: function(slug) {
                    for (let i=0; i<allCategories.length; i++) {
                        if (allCategories[i].slug === slug) {
                            return {
                                category: allCategories[i],
                                subcategory: null,
                                subcategories: allCategories[i].subcategories,
                                isSingleProduct: allCategories[i].is_product_and_category
                            };
                        } else {
                            // Check subcategories
                            let subcategories = allCategories[i].subcategories;
                            if (subcategories === null) {
                                continue;
                            }
                            for (let j=0; j<subcategories.length; j++) {
                                if (subcategories[j].slug === slug) {
                                    return {
                                        category: allCategories[i],
                                        subcategory: subcategories[j],
                                        subcategories: subcategories,
                                        isSingleProduct: subcategories[j].is_product_and_category
                                    }
                                }
                            }
                        }
                    }
                }
            };
        };


        window.reactMain = {
            consts: consts,

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
                window.showLoader();

                // Push a new state to the history
                const newState = {
                    ...prevProps
                };
                const newTitle = product.post_title + " — Kandelaber";
                const newUrl = '/proizvod/' + product.post_name + "/";

                history.pushState(newState, null, newUrl);
                document.title = newTitle;

                transitionOverlayLoader(() => {
                    if (typeof callback === 'function') {
                        callback();
                    }
                }, false, {
                    hide: 300,
                    show: 200
                });
            }
        };

    })();
});