import styles from "./app.module.scss";
import SubcategoriesOverlay from "./SubcategoriesOverlay";
import {useCallback, useEffect, useRef, useState} from "react";
import ProductCategoryPreview from "../../../react/product-category-preview/ProductCategoryPreview";
import SingleProductPreview from "../../../react/single-product-preview/SingleProductPreview";

const SingleCategory = ({category, i, changeSubcategory, parentCategory, fromCategoryPreview, hasProducts, subcategories, isSingleProduct, isLeaf}) => {

    const [showSubcategoriesContent, setShowSubcategoriesContent] = useState(true);
    const [showSubcategoriesOverlay, setShowSubcategoriesOverlay] = useState(false);
    const openSubcategoriesOverlay = useCallback((e) => {
        setShowSubcategoriesContent(false);
        setShowSubcategoriesOverlay(true);
        e.preventDefault();
        e.stopPropagation();
    }, []);
    const singleCategoryDiv = useRef(null);

    const openCategory = useCallback(() => {

        if (isSingleProduct) {
            let previousHistoryState;
            if (isLeaf) {
                previousHistoryState = {
                    category: parentCategory,
                    subcategories: subcategories,
                    subcategory: category,
                    page: "opened-category",
                    isSingleProduct: isSingleProduct
                };
            } else {
                previousHistoryState = {
                    category: category,
                    subcategories: subcategories,
                    subcategory: null,
                    page: "opened-category",
                    isSingleProduct: isSingleProduct
                };
            }
            window.reactMain.openProduct(previousHistoryState, {
                post_title: category.name,
                post_name: category.slug
            }, () => {
                window.renderApp("single-product-preview", <SingleProductPreview product={category} isCategory />)
            });
            return;
        }

        if (typeof changeSubcategory === 'function' && fromCategoryPreview) {
            window.openSubcategory({
                category: parentCategory,
                subcategory: category,
                subcategories: null
            });
            changeSubcategory(category);
            return;
        }
        window.openCategory(showSubcategoriesOverlay, category, subcategories, () => {
            window.renderApp('product-category-preview', <ProductCategoryPreview data={true} category={category} subcategories={category.subcategories} />);
        });
    }, [category, showSubcategoriesOverlay, parentCategory, category, fromCategoryPreview, subcategories, isSingleProduct, isLeaf]);

    useEffect(() => {
        const onPopStateHandler = (e) => {
            setShowSubcategoriesContent(true);
            setShowSubcategoriesOverlay(false);
        };
        window.addEventListener('popstate', onPopStateHandler);
        return () => {
            window.removeEventListener('popstate', onPopStateHandler);
        };
    }, []);

    return (
        <div ref={singleCategoryDiv}
             className={`col-md-3 ${styles.singleCategory}${fromCategoryPreview ? ' ' + styles.subcategoryListing : ''}${hasProducts ? ' ' + styles.hasProducts : ''}`}
             style={{animationDelay: i*250 + "ms"}} onClick={openCategory}>
            <div className={`${styles.content}${!showSubcategoriesContent ? ' ' + styles.noZoom : ''}`}>
                <div className={styles.imagePlaceholder}>
                    <img src={category.image[0]} />
                </div>

                <div className={`${styles.categoryTitle}${!showSubcategoriesContent ? ' ' + styles.noOpacity : ''}`}>
                    {category.name}
                </div>


                {category.subcategories != null &&
                    <>
                        <div className={`${styles.subcategoriesContent}${!showSubcategoriesContent ? ' ' + styles.noOpacity : ''}`} onClick={openSubcategoriesOverlay}>
                            <span>Podkategorije</span>
                            <div className={styles.threeDots}>
                                <i className="fa-solid fa-ellipsis"></i>
                            </div>
                        </div>
                        <SubcategoriesOverlay
                            show={showSubcategoriesOverlay}
                            parentCategory={category}
                            setShowSubcategoriesContent={setShowSubcategoriesContent}
                            setShowSubcategoriesOverlay={setShowSubcategoriesOverlay}
                            subcategories={category.subcategories}
                            singleCategoryDiv={singleCategoryDiv}
                        />
                    </>
                }
            </div>
        </div>
    );

};

export default SingleCategory;