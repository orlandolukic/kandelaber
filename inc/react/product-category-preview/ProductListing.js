import styles from './product-category-preview.module.scss';
import Button from "../button/Button";
import {useCallback, useEffect, useRef, useState} from "react";
import stylesApp from '../../elementor/product-category-listing/react/app.module.scss';
import ProductCategoryPreview from "./ProductCategoryPreview";
import SingleProductPreview from "../single-product-preview/SingleProductPreview";
import SingleProductElement from "./SingleProductElement";

export const PAGE_SIZE = 8;

const getProductsOnPage = (products, page, pageSize) => {
    return products.slice(page*PAGE_SIZE, page*PAGE_SIZE + pageSize);
};

function isButtonNearEnd(button) {
    const windowHeight = window.innerHeight;
    const buttonRect = button.getBoundingClientRect();

    // Adjust the threshold as needed
    const threshold = 200;

    return buttonRect.top <= windowHeight - threshold;
}

const ProductListing = ({category, subcategory, subcategories}) => {

    const [displayNoProducts, setDisplayNoProducts] = useState(false);
    const [products, setProducts] = useState([]);
    const [productsToDisplay, setProductsToDisplay] = useState([]);
    const [page, setPage] = useState(0);
    const [loading, setLoading] = useState(true);
    const [loadingMoreProducts, setLoadingMoreProducts] = useState(false);
    const productElementsPlaceholder = useRef(null);
    const showMoreButtonRef = useRef(null);

    const openProduct = useCallback((product) => {
        window.reactMain.openProduct({
            category: category,
            subcategories: subcategories,
            subcategory: subcategory,
            page: "opened-category",
            isSingleProduct: false
        }, product,() => {
            window.renderApp("single-product-preview", <SingleProductPreview product={product} />, true);
        });
    }, [category, subcategory, subcategories]);

    const showMoreProducts = useCallback(() => {
        if (loadingMoreProducts || !productElementsPlaceholder || !productElementsPlaceholder.current || productsToDisplay.length === products.length) {
            return;
        }
        setLoadingMoreProducts(true);
        setTimeout(() => {
            let productsArr = [...productsToDisplay];
            let subarr = getProductsOnPage(products, page+1, PAGE_SIZE);
            for (let i=0; i<subarr.length; i++) {
                productsArr = [...productsArr, subarr[i]];
            }
            setPage(page + 1);
            setProductsToDisplay(productsArr);
            setLoadingMoreProducts(false);
        }, 500 + Math.random() * 1500);
    }, [products, loadingMoreProducts, page, productsToDisplay, productElementsPlaceholder.current]);

    useEffect(() => {
        const scrollHandler = (e) => {
            if (showMoreButtonRef.current === null) {
                return;
            }

            if (isButtonNearEnd(showMoreButtonRef.current)) {
                showMoreProducts();
            }
        };
        window.addEventListener("scroll", scrollHandler);
        return () => {
            window.removeEventListener("scroll", scrollHandler);
        }
    }, [showMoreProducts]);

    useEffect(() => {

        setPage(0);
        setProductsToDisplay([]);

        if (window.productsFactory.areProductsFetched(category, subcategory)) {
            window.productsFactory.hasProductsInCategory(category, subcategory)
                .then((hasProducts) => {
                    const shouldDisplayNoProducts = !hasProducts && (subcategory !== null || subcategories === null || subcategories.length === 0);
                    setDisplayNoProducts(shouldDisplayNoProducts);

                    const products = window.productsFactory.retrieveProductsSync(category, subcategory);
                    setProducts(products);
                    setProductsToDisplay(getProductsOnPage(products, 0, PAGE_SIZE));
                    setLoading(false);
                })
        } else {

            setLoading(true);
            window.productsFactory.hasProductsInCategory(category, subcategory)
                .then((hasProducts) => {
                    const productsPromise = window.productsFactory.getProductsFromCategory(category, subcategory);
                    productsPromise
                        .then((products) => {
                            setTimeout(function () {
                                const shouldDisplayNoProducts = !hasProducts && (subcategory !== null || subcategories === null || subcategories.length === 0);
                                setDisplayNoProducts(shouldDisplayNoProducts);
                                setProducts(products);
                                setProductsToDisplay(getProductsOnPage(products, 0, PAGE_SIZE));
                                setLoading(false);
                            }, 250);
                            jQuery("." + styles.loadingContainer).addClass(styles.dismiss);
                        });
                });
        }

        return () => {
            window.productsFactory.rejectAllPromises(category, subcategory);
        }
    }, [category, subcategory, subcategories]);

    const showAllProducts = useCallback(() => {
        window.showLoader();
        window.location.href = "/proizvodi";
    }, []);

    if ((subcategory === null || subcategory === undefined) && !category.has_products && subcategories && subcategories.length > 0) {
        return;
    }

    if (loading) {
        return <>
            <div className={styles.productListing}>
                <div className={"container"}>
                    <div className={"row"}>
                        <div className={"col-md-12"}>
                            <div></div>
                            <div className={`${styles.loadingContainer}`}>
                                <div className={styles.content}>
                                    <div className={stylesApp.loader}></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    }

    if (displayNoProducts) {
        return <>
            <div className={styles.productListing}>
                <div className={"container"}>
                    <div className={"row"}>
                        <div className={"col-md-12"}>
                            <div data-id={"1"} className={styles.noProducts}>
                                <i className="fa-solid fa-arrow-up-right-dots fa-4x"></i>
                                <div className={styles.title}>
                                    Trenutno nema proizvoda u ovoj kategoriji...
                                </div>
                                <div className={styles.subtitle}>
                                    ...ali to ne znači da mi nismo već krenuli u njihovu nabavku! Molimo Vas za strpljenje.
                                </div>
                                <div className={styles.button}>
                                    <Button onClick={showAllProducts}>
                                        Sve kategorije
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    }

    const productElements = productsToDisplay.map((product, i) => {
        return <SingleProductElement product={product} i={i} delayIndex={i} onClick={openProduct.bind(null, product)} />
    });

    return (
        <>
            <div className={styles.productListing}>
                <div className={"container"}>
                    <div className={"row"}>
                        <div className={`col-md-12 ${styles.productsCatalogue}`}>
                            <div className={styles.titlePlaceholder}>
                                <div className={styles.icon}>
                                    <i className="fa-solid fa-bookmark fa-2x"></i>
                                </div>
                                <div className={styles.title}>
                                    Naš katalog
                                </div>
                            </div>
                            <div className={styles.description}>Neprestano proširujemo naš asortiman & pratimo modne trendove</div>
                        </div>
                    </div>
                    <div ref={productElementsPlaceholder} className={"row"}>
                        {productElements}
                    </div>
                    {(products.length - productsToDisplay.length > 0) &&
                        <div className={"row"}>
                            <div className={`col-md-12 ${styles.showMoreButtonPlaceholder}`}>
                                <div ref={showMoreButtonRef} className={`${styles.showMoreButton}${loadingMoreProducts ? ' ' + styles.isLoading : ''}`} onClick={showMoreProducts}>
                                    <div className={styles.icon}>
                                        {loadingMoreProducts && <i className="fa-solid fa-circle-notch fa-spin fa-2x"></i>}
                                        {!loadingMoreProducts && <i className="fa-regular fa-hand-point-right fa-2x"></i>}
                                    </div>
                                    <div className={styles.text}>
                                        {!loadingMoreProducts && <>Prikažite još proizvoda</>}
                                        {loadingMoreProducts && <>Učitavanje...</>}
                                    </div>
                                </div>
                            </div>
                        </div>
                    }

                    {(products.length - productsToDisplay.length === 0 && page > 0) &&
                        <div className={"row"}>
                            <div className={`col-md-12 ${styles.showMoreButtonPlaceholder}`}>
                                <div className={`${styles.showMoreButton} ${styles.noActionButton}`}>
                                    <div className={styles.icon}>
                                        <i className="fa-solid fa-paper-plane fa-2x"></i>
                                    </div>
                                    <div className={styles.text}>
                                        To je to. Pregledali ste sve!
                                    </div>
                                </div>
                            </div>
                        </div>
                    }
                </div>
            </div>
        </>
    )
};

export default ProductListing;