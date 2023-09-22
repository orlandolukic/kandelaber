import styles from "./app.module.scss";
import SubcategoriesOverlay from "./SubcategoriesOverlay";
import {useCallback, useState} from "react";

const SingleCategory = ({category}) => {

    const [showSubcategoriesContent, setShowSubcategoriesContent] = useState(true);
    const [showSubcategoriesOverlay, setShowSubcategoriesOverlay] = useState(false);
    const openSubcategoriesOverlay = useCallback(() => {
        setShowSubcategoriesContent(false);
        setShowSubcategoriesOverlay(true);
    }, []);

    return (
        <div className={`col-md-3 ${styles.singleCategory}`} style={{animationDelay: i*500 + "ms"}}>
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
                            categoryName={category.name}
                            setShowSubcategoriesContent={setShowSubcategoriesContent}
                            setShowSubcategoriesOverlay={setShowSubcategoriesOverlay}
                            subcategories={category.subcategories}
                        />
                    </>
                }
            </div>
        </div>
    );

};

export default SingleCategory;