import styles from './app.module.scss';
import {useCallback} from "react";

const SubcategoriesOverlay = ({show, categoryName, setShowSubcategoriesOverlay, setShowSubcategoriesContent, subcategories}) => {

    const hideOverlay = useCallback(() => {
        setShowSubcategoriesContent(true);
        setShowSubcategoriesOverlay(false);
    }, []);

    const subcategoriesListElements = subcategories.map((subcategory, i) =>
        <div className={styles.singleSubcategory}>
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
      <div className={`${styles.subcategoriesOverlay}${!show ? ' ' + styles.noDisplay : ''}`}>
          <div className={styles.nameContainer}>
              <div className={styles.icon} onClick={hideOverlay}>
                  <i className={'fa fa-chevron-left'} />
              </div>
              <div className={styles.text}>
                  <div className={styles.smallText}>Sve podkategorije</div>
                  <div className={styles.categoryName}>{categoryName}</div>
              </div>
          </div>
          <div className={styles.subcategoryList}>
              <div className={styles.subcategoryListPlaceholder}>
                  {subcategoriesListElements}
              </div>
          </div>
          <div className={styles.seeAllCategories}>
              <i className="fa-solid fa-eye"></i>
              <span className={styles.text}>Pogledajte sve</span>
          </div>
      </div>
    );
};

export default SubcategoriesOverlay;