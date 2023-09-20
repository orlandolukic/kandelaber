import styles from './app.module.scss';

const Categories = ({categories}) => {


    const categoriesElements = categories.map((category, i) => (
        <div className={`col-md-3 ${styles.singleCategory}`} key={category.term_id} style={{animationDelay: i*500 + "ms"}}>
            <div className={styles.content}>
                <div className={styles.imagePlaceholder}>
                    <img src={category.image[0]} />
                </div>
                <div className={styles.categoryTitle}>
                    {category.name}
                </div>

                {category.subcategories != null &&
                <div className={styles.subcategoriesContent}>
                    <span>Podkategorije</span>
                    <div className={styles.threeDots}>
                        <i className="fa-solid fa-ellipsis"></i>
                    </div>
                </div>
                }
            </div>
        </div>
    ));

    return (
        <div className={`row ${styles.categoriesPlaceholder}`}>
            {categoriesElements}
        </div>
    )
};

export default Categories;