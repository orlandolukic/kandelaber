import styles from './single-product-preview.module.scss';
import Slider from "./Slider";
import {useCallback} from "react";

const ChooseFromGallery = ({variations, pauseSlideshow, metadata}) => {

    pauseSlideshow = pauseSlideshow !== undefined ? pauseSlideshow : false;

    const getImageSource = useCallback((element) => {
        return element.image.src;
    }, []);

    return <>
        <div className={styles.variationsModal}>
            <div className={`${styles.fastCollectionPlaceholder} ${styles.fastCollectionPlaceholderInModal}`}>
                <div className={styles.text}>
                    <div className={styles.title}>
                        <i className={metadata.modal_icon}></i>
                        <span className={styles.titleText}>{metadata.modal_title}</span>
                    </div>
                    <div className={styles.subtitle}>
                        {metadata.modal_subtitle}
                    </div>
                </div>
            </div>
            <Slider id={"splide-variations"}
                    getImageSource={getImageSource}
                    list={variations}
                    maxHeight={500}
                    thumbnail={'horizontal'}
                    timeout={2000} />
        </div>
    </>

};

export default ChooseFromGallery;