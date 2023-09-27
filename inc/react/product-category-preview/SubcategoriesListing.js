import styles from "./product-category-preview.module.scss";


const SubcategoriesListing = ({category, subcategory, subcategories}) => {

    // Check if subcategories exist within current category
    if (subcategory || !subcategories || subcategories.length === 0) {
        return;
    }

    const subcategoriesElements = subcategories.map((subcategory, i) => (
        <div className={"col-md-12"} key={i}>
            <div >{subcategory.name}</div>
        </div>

    ))

    return (
        <>
            <div className={`container ${styles.subcategoryListing}`}>
                <div className={"row"}>
                    <div className={"col-md-12"}>
                        <div className={styles.title}>
                            Odaberite podkategoriju
                        </div>
                    </div>
                </div>
                <div className={"row"}>
                    {subcategoriesElements}
                </div>
            </div>
        </>
    )
};

export default SubcategoriesListing;