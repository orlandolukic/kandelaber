import styles from './single-product-element.module.scss';
import {PAGE_SIZE} from "./ProductListing";

const SingleProductElement = ({product, delayIndex, i, onClick, className}) => {
    let delay = delayIndex === undefined ? 0 : 300*(i % PAGE_SIZE);
    let styleObj = delayIndex === undefined ? null : {animationDelay: delay + "ms"};
    className = typeof className !== 'undefined' ? ' ' + className : '';
    return (
        <div className={`col-md-3 col-sm-6 ${styles.singleProduct}${delayIndex === undefined ? ' ' + styles.shown : ''}${className}`} key={i} style={styleObj} onClick={onClick}>
            <div className={styles.content}>
                <div className={styles.image}>
                    <img src={product.featured_image[0]} />
                </div>
                <div className={styles.title}>
                    {product.post_title}
                </div>
            </div>
        </div>
    );
};

export default SingleProductElement;