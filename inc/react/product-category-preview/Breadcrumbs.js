import styles from "./product-category-preview.module.scss";
import {useCallback, useEffect} from "react";
import SubcategoriesListing from "./SubcategoriesListing";

const Breadcrumbs = ({category, subcategory, changeCategory, changeSubcategory, subcategories, changeSubcategories, onOpenCategory,
                     blockLeaf}) => {

    useEffect(() => {
        tippy('#tooltipAllProducts', {
            content: "Pogledajte sve proizvode",
            offset: [0, 20],
            placement: 'bottom'
        });
    }, []);

    const showProducts = useCallback(() => {
        window.showLoader();
        window.location.href = "/proizvodi";
    }, []);

    const openCategory = useCallback((i) => {

        // Check if leaf category is being clicked on
        if (i === 1 && blockLeaf) {
            return;
        }

        if (onOpenCategory !== undefined && typeof onOpenCategory === 'function') {
            onOpenCategory(i);
        } else if (subcategory !== undefined && subcategory !== null) {

            // Push a new state to the history
            const newState = {
                page: "opened-category",
                category: category,
                subcategory: null,
                subcategories: subcategories,
                isSingleProduct: false
            };
            const newTitle = category.name + " — Kandelaber";
            const newUrl = '/proizvodi/' + category.slug + '/';
            history.pushState(newState, null, newUrl);
            document.title = newTitle;

            changeSubcategory(null);
            window.showLoader();
        }
    }, [subcategory, category, subcategories, onOpenCategory]);

    return (
        <div className={styles.breadcrumbs}>
            <div className={`container-md`}>
                <div className={"row"}>
                    <div className={`col-md-12 col-sm-12`}>
                        <div className={styles.content}>
                            <div onClick={showProducts} className={styles.showAllProductsIcon}>
                                <i id={'tooltipAllProducts'} className="fa-solid fa-shop fa-2x"></i>
                            </div>

                            <div className={styles.arrow}>
                                <i className="fa-solid fa-chevron-right"></i>
                            </div>

                            <div className={styles.categoryNameDisplay}>
                                <div className={styles.textContent}>
                                    <div className={styles.text}>Kategorija</div>
                                    <div className={styles.name} onClick={openCategory.bind(null, 0)}>{category.name}</div>
                                </div>
                            </div>

                            {subcategory &&
                                <>
                                    <div className={styles.arrow}>
                                        <i className="fa-solid fa-chevron-right"></i>
                                    </div>
                                    <div className={styles.categoryNameDisplay}>

                                        <div className={styles.textContent}>
                                            <div className={styles.text}>Podkategorija</div>
                                            <div className={styles.name}  onClick={openCategory.bind(null, 1)}>{subcategory.name}</div>
                                        </div>
                                    </div>
                                </>
                            }
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
};

export default Breadcrumbs;