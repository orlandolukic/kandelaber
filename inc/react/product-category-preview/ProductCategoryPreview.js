
import styles from './product-category-preview.module.scss';
import {useCallback, useEffect, useState} from "react";
import Breadcrumbs from "./Breadcrumbs";
import SubcategoriesListing from "./SubcategoriesListing";
import SingleCategory from "../../elementor/product-category-listing/react/SingleCategory";

const ProductCategoryPreview = ({category, subcategory, subcategories}) => {

    const [categoryState, setCategoryState] = useState(category);
    const [subcategoryState, setSubcategoryState] = useState(subcategory);
    const [subcategoriesState, setSubcategoriesState] = useState(subcategories);
    const [title, setTitle] = useState("");
    const [imageSrc, setImageSrc] = useState("");

    useEffect(() => {
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
        let title, imageSrc;
        if (subcategoryState === null || subcategoryState === undefined) {
            title = categoryState.name;
            imageSrc = categoryState.image[0];
        } else {
            title = subcategoryState.name;
            imageSrc = subcategoryState.image[0];
        }
        setTitle(title);
        setImageSrc(imageSrc);
        return () => {
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

                <div className={'container'}>
                    <div className={'row'}>
                        <div className={`col-md-12 ${styles.categoryInfo}`}>
                            <div className={styles.categoryName}>{title}</div>
                            <div className={styles.categoryDescription}>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sit amet dui eu magna dignissim euismod.
                                Vivamus consectetur arcu vel erat pharetra, et imperdiet arcu pretium.
                                Nam placerat, enim in porta feugiat, dolor erat lobortis augue, quis dapibus est diam vitae felis.
                            </div>
                            <div className={styles.categoryImage}>
                                <img src={imageSrc} />
                            </div>
                        </div>
                    </div>
                </div>

                <SubcategoriesListing
                    category={categoryState}
                    subcategory={subcategoryState}
                    subcategories={subcategoriesState}
                    changeSubcategory={setSubcategoryState}
                    fromCategoryPreview={true}
                />

            </div>
        </>
    );
};

export default ProductCategoryPreview;