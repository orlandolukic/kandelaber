import styles from "./product-category-preview.module.scss";
import {useCallback, useEffect} from "react";

const Breadcrumbs = ({category, subcategory}) => {

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

    return (
        <div className={styles.breadcrumbs}>
            <div className={`container`}>
                <div className={"row"}>
                    <div className={`col-md-12`}>
                        <div className={styles.content}>
                            <div onClick={showProducts} className={styles.showAllProductsIcon}>
                                <i id={'tooltipAllProducts'} className="fa-solid fa-shop fa-2x"></i>
                            </div>

                            <div className={styles.arrow}>
                                <i className="fa-solid fa-chevron-right"></i>
                            </div>

                            <div className={styles.categoryNameDisplay}>
                                <div className={styles.image}>
                                    <img src={category.image[0]} />
                                </div>
                                <div className={styles.textContent}>
                                    <div className={styles.text}>Kategorija</div>
                                    <div className={styles.name}>{category.name}</div>
                                </div>
                            </div>

                            {subcategory &&
                                <>
                                    <div className={styles.arrow}>
                                        <i className="fa-solid fa-chevron-right"></i>
                                    </div>
                                    <div className={styles.categoryNameDisplay}>
                                        <div className={styles.image}>
                                            <img src={subcategory.image[0]} />
                                        </div>
                                        <div className={styles.textContent}>
                                            <div className={styles.text}>Podkategorija</div>
                                            <div className={styles.name}>{subcategory.name}</div>
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