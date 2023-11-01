import styles from './app.module.scss';
import {useCallback, useEffect, useRef, useState} from "react";
import {createRoot} from "react-dom/client";
import ProductCategoryPreview from "../../../react/product-category-preview/ProductCategoryPreview";
import SingleProductPreview from "../../../react/single-product-preview/SingleProductPreview";

const SubcategoriesOverlay = ({show, parentCategory, setShowSubcategoriesOverlay, setShowSubcategoriesContent, subcategories, singleCategoryDiv}) => {

    const hideOverlay = useCallback(() => {
        setShowSubcategoriesContent(true);
        setShowSubcategoriesOverlay(false);
    }, []);
    const seeAllCategoriesDiv = useRef(null);
    const nameContainerDiv = useRef(null);
    const subcategoriesListDiv = useRef(null);
    const subcategoriesOverlayDiv = useRef(null);
    const [subcategoriesListDivHeight, setSubcategoriesListDivHeight] = useState("auto");

    useEffect(() => {
        if (seeAllCategoriesDiv.current !== null &&
            nameContainerDiv.current !== null &&
            subcategoriesListDiv.current !== null &&
            subcategoriesOverlayDiv.current !== null &&
            singleCategoryDiv.current !== null
        ) {
            const totalHeight = singleCategoryDiv.current.clientHeight;
            const nameContainerDivHeight = nameContainerDiv.current.clientHeight;
            const seeAllCategoriesDivHeight = seeAllCategoriesDiv.current.clientHeight;
            const theRest = totalHeight - nameContainerDivHeight - seeAllCategoriesDivHeight;
            setSubcategoriesListDivHeight(theRest + "px");

            if (show) {
                subcategoriesListDiv.current.scrollTop = 0;
            }
        }
    }, [
        seeAllCategoriesDiv.current,
        nameContainerDiv.current,
        subcategoriesListDiv.current,
        subcategoriesOverlayDiv.current,
        singleCategoryDiv.current,
        subcategories,
        show
    ]);

    const openCategory = useCallback(() => {
        window.openCategory(false, parentCategory, subcategories, () => {
            window.renderApp("product-category-preview", <ProductCategoryPreview data={true} category={parentCategory} subcategories={subcategories} />);
        });
    }, [parentCategory.slug, parentCategory.name, subcategories]);

    const openSubcategory = useCallback((subcategory) => {
        if (subcategory.is_product_and_category) {
            window.reactMain.openProduct({
                category: parentCategory,
                subcategory: subcategory,
                subcategories: subcategories
            }, {
                post_title: subcategory.name,
                post_name: subcategory.slug
            }, () => {
                window.renderApp(window.reactMain.consts.SINGLE_PRODUCT_PREVIEW, <SingleProductPreview slug={subcategory.slug} />)
            });
        } else {
            window.openSubcategory({
                category: parentCategory,
                subcategory: subcategory,
                subcategories: subcategories
            }, () => {
                window.renderApp("product-category-preview", <ProductCategoryPreview data={true}
                                                                                     category={parentCategory}
                                                                                     subcategory={subcategory}
                                                                                     subcategories={subcategories}/>);
            });
        }
    }, []);

    // Mapping of subcategories
    const subcategoriesListElements = subcategories.map((subcategory, i) =>
        <div className={styles.singleSubcategory} onClick={openSubcategory.bind(null, subcategory)}>
            <div className={styles.icon}>
                <img src={subcategory.image[0]} />
            </div>
            <div className={styles.name}>
                {subcategory.name}
            </div>
            <div className={styles.openArrow}>
                <i className="fa-solid fa-arrow-up-right-from-square"></i>
            </div>
        </div>
    );

    return (
      <div ref={subcategoriesOverlayDiv} className={`${styles.subcategoriesOverlay}${!show ? ' ' + styles.noDisplay : ''}`}>
          <div ref={nameContainerDiv} className={styles.nameContainer}>
              <div className={styles.icon} onClick={hideOverlay}>
                  <i className={'fa fa-chevron-left'} />
              </div>
              <div className={styles.text}>
                  <div className={styles.smallText}>Sve podkategorije</div>
                  <div className={styles.categoryName}>{parentCategory.name}</div>
              </div>
          </div>
          <div ref={subcategoriesListDiv} className={styles.subcategoryList} style={{height: subcategoriesListDivHeight}}>
              <div className={styles.subcategoryListPlaceholder}>
                  {subcategoriesListElements}
              </div>
          </div>
          <div ref={seeAllCategoriesDiv} className={styles.seeAllCategories} onClick={openCategory}>
              <i className="fa-solid fa-eye"></i>
              <span className={styles.text}>Pogledajte sve</span>
          </div>
      </div>
    );
};

export default SubcategoriesOverlay;