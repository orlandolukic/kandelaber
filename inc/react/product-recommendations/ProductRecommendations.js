import styles from './product-recommendations.module.scss';
import SingleProductElement from "../product-category-preview/SingleProductElement";
import Button from "../button/Button";

const ProductRecommendations = ({ recommendations }) => {

    if (recommendations === null || recommendations === undefined) {
        return null;
    }

    return <>
        <div className={styles.productRecommendationsContainer}>
            <div className={"container"}>
                <div className={"row"}>
                    <div className={"col-md-8"}>
                        <div className={styles.titlePlaceholder}>
                            <div className={styles.icon}>
                                <i className="fa-solid fa-layer-group fa-3x fa-beat"></i>
                            </div>

                            <div className={styles.text}>
                                <div className={styles.title}>
                                    Takođe atraktivno
                                </div>
                                <div className={styles.subtitle}>
                                    Ljudi kupuju i ove proizvode
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className={`col-md-4 ${styles.buttonPlaceholder}`}>
                        <Button>Pogledajte sve</Button>
                    </div>
                </div>

                <div className={`row ${styles.recommendationsList}`}>
                    {recommendations.map((recommendation, i) => {
                        return (
                            <SingleProductElement product={recommendation} i={i} />
                        );
                    })}
                </div>
            </div>
        </div>
    </>
};

export default ProductRecommendations;