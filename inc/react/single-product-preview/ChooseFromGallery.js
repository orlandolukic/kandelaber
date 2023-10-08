import styles from './single-product-preview.module.scss';
import Slider from "./Slider";
import {useCallback} from "react";

const ChooseFromGallery = ({variations}) => {

    const getImageSource = useCallback((element) => {
        return element.image.src;
    }, []);

    return <>
        <div className={styles.variationsModal}>
            <div className={`${styles.chooseCable} ${styles.chooseCableInModal}`}>
                <div className={styles.text}>
                    <div className={styles.title}>
                        <i className="fa-solid fa-wand-magic-sparkles"></i>
                        <span className={styles.titleText}>Odabirite boju i tip kabla</span>
                    </div>
                    <div className={styles.subtitle}>
                        Kod nas možete pogledati kablove koje možemo primeniti na Vašem projektu
                    </div>
                </div>
            </div>
            <Slider id={"splide-variations"} getImageSource={getImageSource} list={variations} maxHeight={500} thumbnail={'horizontal'} timeout={2000} />
        </div>
    </>

};

export default ChooseFromGallery;