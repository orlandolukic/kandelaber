
import styles from './product-category-preview.module.scss';
import {useCallback, useEffect, useState} from "react";
import Breadcrumbs from "./Breadcrumbs";
import SubcategoriesListing from "./SubcategoriesListing";
import SingleCategory from "../../elementor/product-category-listing/react/SingleCategory";
import ProductListing from "./ProductListing";

const ProductCategoryPreview = ({category, subcategory, subcategories, renderedAgain}) => {

    const [categoryState, setCategoryState] = useState(category);
    const [subcategoryState, setSubcategoryState] = useState(subcategory);
    const [subcategoriesState, setSubcategoriesState] = useState(subcategories);
    const [fetchProducts, setFetchProducts] = useState(false);
    const [title, setTitle] = useState("");
    const [imageSrc, setImageSrc] = useState("");

    useEffect(() => {
        let subcategories = window.reactMain.categoriesManager.getSubcategoriesForCategoryBySlug(category.slug);
        setSubcategoriesState(subcategories);
        setFetchProducts(true);

        history.replaceState({
            page: "opened-category",
            category: category,
            subcategory: subcategory === undefined ? null : subcategory,
            subcategories: subcategories === undefined ? null : subcategories
        }, null);
    }, []);

    useEffect(() => {
        let timeout;
        const handler = (e) => {
            if (e.state.page === "opened-category") {
                timeout = setTimeout(() => {
                    window.hideLoader();
                }, 500);
            }
        };
        window.addEventListener('popstate', handler);
        return () => {
            window.removeEventListener('popstate', handler);
            clearTimeout(timeout);
        };
    }, [renderedAgain]);

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
                    blockLeaf={true}
                    category={categoryState}
                    changeCategory={setCategoryState}
                    subcategory={subcategoryState}
                    changeSubcategory={setSubcategoryState}
                    subcategories={subcategoriesState}
                    changeSubcategories={setSubcategoriesState}
                />

                <div className={'container'}>
                    <div className={'row'}>
                        <div className={`col-md-12 ${styles.categoryInfo}`}>
                            <div className={styles.categoryName}>{title}</div>
                            <div className={styles.categoryDescription}>
                                {categoryState.category_description}
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

                <ProductListing
                    category={categoryState}
                    subcategory={subcategoryState}
                    subcategories={subcategoriesState}
                    fetchProducts={fetchProducts}
                />

            </div>
        </>
    );
};

export default ProductCategoryPreview;