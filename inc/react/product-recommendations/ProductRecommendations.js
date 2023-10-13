import styles from './product-recommendations.module.scss';
import SingleProductElement from "../product-category-preview/SingleProductElement";
import Button from "../button/Button";
import {useCallback, useEffect} from "react";
import ProductCategoryPreview from "../product-category-preview/ProductCategoryPreview";
import SingleProductPreview from "../single-product-preview/SingleProductPreview";

const ProductRecommendations = ({ product, recommendations, recommended_products_category }) => {

    const openAllCategories = useCallback(() => {

        window.showLoader();

        setTimeout(() => {
            let props = window.reactMain.categoriesManager.getPropsForComponentRendering(recommended_products_category.slug);
            console.log("props", props);
            window.renderApp(window.reactMain.consts.PRODUCT_CATEGORY_PREVIEW,
                <ProductCategoryPreview {...props} />,
                true,
                true
            );
        }, 500);

        // Push a new state to the history
        const newState = {
            page: "single-product",
            product: product,
            recommended_products: recommendations,
            recommended_products_category: recommended_products_category
        };
        const newTitle = recommended_products_category.name + " — Kandelaber";
        let toShow = recommendations[0].categories.length > 1 ? 1 : 0;
        const newUrl = '/proizvodi' +  window.reactMain.categoriesManager.getURLPathForCategories(recommendations[0].categories, toShow);

        history.pushState(newState, null, newUrl);
        document.title = newTitle;

        // Scroll to top of the document
        window.reactMain.scrollToTop();
    }, [product]);

    const selectProduct = useCallback((recommendation) => {
        window.showLoader();

        setTimeout(() => {
            window.renderApp(window.reactMain.consts.SINGLE_PRODUCT_PREVIEW,
                <SingleProductPreview
                    slug={recommendation.post_name}
                />,
                true,
                true
            );
        }, 150);

        // Push a new state to the history
        const newState = {
            page: "single-product",
            slug: recommendation.post_name
        };
        const newTitle = recommendation.post_title + " — Kandelaber";
        const newUrl = '/proizvod/' + recommendation.post_name + "/";

        history.pushState(newState, null, newUrl);
        document.title = newTitle;
    }, []);

    useEffect(() => {
        console.log(recommendations);
    }, []);

    if (recommendations === null || recommendations === undefined) {
        return null;
    }

    return <>
        <div className={styles.productRecommendationsContainer}>
            <div className={"container"}>
                <div className={"row"}>
                    <div className={"col-md-8"}>
                        <div className={styles.titlePlaceholder}>
                            <div className={styles.icon}>
                                <i className="fa-solid fa-layer-group fa-3x"></i>
                            </div>

                            <div className={styles.text}>
                                <div className={styles.title}>
                                    Takođe atraktivno
                                </div>
                                <div className={styles.subtitle}>
                                    Ljudi kupuju i ove proizvode
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className={`col-md-4 ${styles.buttonPlaceholder}`}>
                        <Button onClick={openAllCategories}>Pogledajte sve</Button>
                    </div>
                </div>

                <div className={`row ${styles.recommendationsList}`}>
                    {recommendations.map((recommendation, i) => {
                        return (
                            <SingleProductElement product={recommendation} i={i} onClick={selectProduct.bind(null, recommendation)} />
                        );
                    })}
                </div>
            </div>
        </div>
    </>
};

export default ProductRecommendations;