
import styles from './product-category-preview.module.scss';
import {useCallback, useEffect, useState} from "react";
import Breadcrumbs from "./Breadcrumbs";
import SubcategoriesListing from "./SubcategoriesListing";

const ProductCategoryPreview = ({category, subcategory, subcategories}) => {

    const [categoryState, setCategoryState] = useState(category);
    const [subcategoryState, setSubcategoryState] = useState(subcategory);
    const [subcategoriesState, setSubcategoriesState] = useState(subcategories);

    useEffect(() => {
        console.log(subcategoriesState);
        const handler = (e) => {
            console.log(e);
            if (e.state.page === "opened-category") {
                setTimeout(() => {
                    setCategoryState(e.state.category);
                    setSubcategoryState(e.state.subcategory);
                    window.hideLoader();
                }, 200);
            }
        };
        window.addEventListener('popstate', handler);
        return () => {
            window.removeEventListener('popstate', handler);
        };
    }, []);

    // Handle category change
    useEffect(() => {
        let timeout;
        timeout = window.setTimeout(() => {
            window.hideLoader();
        }, 500);
        return () => {
            console.log("cleared");
            window.clearTimeout(timeout);
        };
    }, [subcategoryState, categoryState]);

    return (
        <>
            <div className={` ${styles.productCategoryPreviewContainer}`}>

                <Breadcrumbs
                    category={categoryState}
                    changeCategory={setCategoryState}
                    subcategory={subcategoryState}
                    changeSubcategory={setSubcategoryState}
                />

                <SubcategoriesListing category={categoryState} subcategory={subcategoryState} subcategories={subcategoriesState} />

            </div>
        </>
    );
};

export default ProductCategoryPreview;