import styles from "./product-category-preview.module.scss";
import stylesApp from '../../elementor/product-category-listing/react/app.module.scss';
import SingleCategory from "../../elementor/product-category-listing/react/SingleCategory";


const SubcategoriesListing = ({category, subcategory, subcategories, changeSubcategory, fromCategoryPreview}) => {

    // Check if subcategories exist within current category
    if (subcategory || !subcategories || subcategories.length === 0) {
        return;
    }

    const subcategoriesElements = subcategories.map((subcategory, i) => (
        <SingleCategory
            category={subcategory}
            i={i}
            key={category.term_id}
            changeSubcategory={changeSubcategory}
            parentCategory={category}
            fromCategoryPreview={fromCategoryPreview}
            hasProducts={category.has_products}
        />
    ));

    return (
        <>
            <div className={`${styles.subcategoryListing}`}>
                <div className={"container"}>
                    <div className={"row"}>
                        <div className={"col-md-10"}>
                            <div className={styles.title}>
                                Odaberite podkategoriju
                            </div>
                        </div>
                    </div>
                    <div className={`row ${stylesApp.categoriesPlaceholder}`}>
                        {subcategoriesElements}
                    </div>
                </div>
            </div>
        </>
    )
};

export default SubcategoriesListing;