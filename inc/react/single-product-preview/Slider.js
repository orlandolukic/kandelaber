import styles from "./slider.module.scss";
import './slider.scss';
import {useEffect, useRef, useState} from "react";

const Slider = ({id, list, getImageSource, maxHeight, hasShadow, slideChanged, thumbnail, timeout, pauseSlideshow}) => {

    const splideRef = useRef(null);
    const splideThumbnailRef = useRef(null);
    const [loaded, setLoaded] = useState(false);
    const timeoutInterval = timeout === undefined ? 3000 : timeout;

    useEffect(() => {

        setTimeout(() => {
            setLoaded(true);
        }, 600);

        setTimeout(() => {
            splideRef.current = new Splide("#" + id, {
                pauseOnHover: true,
                autoplay: true,
                interval: timeoutInterval,
                rewind: true,
                pagination: false
            });
            if (typeof slideChanged === 'function') {
                splideRef.current.on('move', function (newIndex, prevIndex, destIndex) {
                    slideChanged(newIndex, prevIndex, destIndex);
                });
            }

            if (typeof thumbnail !== 'undefined') {
                splideThumbnailRef.current = new Splide("#" + id + "-thumbnail", {
                    autoplay: false,
                    isNavigation: true,
                    fixedWidth: 100,
                    fixedHeight: 60,
                    rewind: true,
                    pagination: false,
                    direction: thumbnail === "vertical" ? 'ttb' : 'ltr',
                    height: "100%"
                });
                splideRef.current.sync(splideThumbnailRef.current);
                splideRef.current.mount();
                splideThumbnailRef.current.mount();
            } else {
                splideRef.current.mount();
            }
        }, 500);
    }, []);

    useEffect(() => {
        if (splideRef.current === null || pauseSlideshow === undefined) {
            return;
        }
        if (pauseSlideshow) {
            splideRef.current.Components.Autoplay.pause();
            if (splideThumbnailRef.current !== null) {
                splideThumbnailRef.current.Components.Autoplay.pause();
            }
        } else {
            splideRef.current.Components.Autoplay.play();
            if (splideThumbnailRef.current !== null) {
                splideThumbnailRef.current.Components.Autoplay.play();
            }
        }
    }, [pauseSlideshow, splideRef.current, splideThumbnailRef.current]);

    return <>
        <div className={`${styles.sliderPlaceholder}${!loaded ? " " + styles.isLoading : ""}${thumbnail !== undefined ? " " + styles[thumbnail + "Thumbnail"] : ""}`}>

            {thumbnail === "vertical" &&
                <section
                    id={id+"-thumbnail"}
                    className={`splide splide-thumbnail splide-thumbnail-vertical ${styles.thumbnail} ${styles.verticalThumbnail}`}
                >
                    <div className="splide__track">
                        <ul className="splide__list">
                            {list.map((listElement, i) => <>
                                <li key={i} className={`splide__slide ${styles.singleSlideContainer}`}>
                                    <img src={getImageSource(listElement)} />
                                </li>
                            </>)}
                        </ul>
                    </div>
                </section>
            }

            <section id={id} className={`splide ${styles.splideSlider}${hasShadow ? " " + styles.hasShadow : ""}`}>

                <div className={`splide__arrows ${styles.arrows}`}>
                    <button className={`splide__arrow splide__arrow--prev ${styles.arrow} ${styles.arrowLeft}`}>
                        <i className={`fa-solid fa-chevron-left`}></i>
                    </button>
                    <button className={`splide__arrow splide__arrow--next ${styles.arrow} ${styles.arrowRight}`}>
                        <i className={`fa-solid fa-chevron-right`}></i>
                    </button>
                </div>

                <div className={`splide__track`}>
                    <ul className={`splide__list ${styles.__list}`}>
                        {list.map((listElement, i) => <>
                            <li key={i} className={`splide__slide ${styles.singleSlideContainer}`}>
                                <img src={getImageSource(listElement)} style={{maxHeight: maxHeight + "px"}} />
                            </li>
                        </>)}
                    </ul>
                </div>
            </section>

            {thumbnail === "horizontal" &&
                <section
                    id={id+"-thumbnail"}
                    className={`splide splide-thumbnail splide-thumbnail-horizontal ${styles.thumbnail} ${styles.horizontalThumbnail}`}
                >
                    <div className="splide__track">
                        <ul className="splide__list">
                            {list.map((listElement, i) => <>
                                <li key={i} className={`splide__slide ${styles.singleSlideContainer}`}>
                                    <img src={getImageSource(listElement)} />
                                </li>
                            </>)}
                        </ul>
                    </div>
                </section>
            }

            {!loaded &&
                <div className={styles.loaderPlaceholder}>
                    <div className={styles.loader}></div>
                </div>
            }

        </div>
    </>
};

export default Slider;