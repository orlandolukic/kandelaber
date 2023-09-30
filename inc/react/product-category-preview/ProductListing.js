import styles from './product-category-preview.module.scss';
import Button from "../button/Button";
import {useCallback, useEffect, useState} from "react";

const ProductListing = ({category, subcategory, subcategories}) => {

    const [displayNoProducts, setDisplayNoProducts] = useState(false);
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {

        setLoading(true);
        window.productsFactory.hasProductsInCategory(category, subcategory)
            .then((hasProducts) => {
                const productsPromise = window.productsFactory.getProductsFromCategory(category, subcategory);
                productsPromise
                    .then((products) => {
                        const shouldDisplayNoProducts = !hasProducts && (subcategory !== null || subcategories === null || subcategories.length === 0);
                        setDisplayNoProducts(shouldDisplayNoProducts);

                        setProducts(products);
                        setLoading(false);
                    });
            });

    }, [category, subcategory, subcategories]);

    const showAllProducts = useCallback(() => {
        window.showLoader();
        window.location.href = "/proizvodi";
    }, []);

    if (loading) {
        return <>
            LOADING...
        </>
    }

    if (displayNoProducts) {
        return <>
            <div className={styles.productListing}>
                <div className={"container"}>
                    <div className={"row"}>
                        <div className={"col-md-12"}>
                            <div className={styles.noProducts}>
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

    return (
        <>
            <div className={styles.productListing}>
                <div className={"container"}>
                    <div className={"row"}>
                        <div className={"col-md-3"}>
                            dfskjkdsjf
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
};

export default ProductListing;