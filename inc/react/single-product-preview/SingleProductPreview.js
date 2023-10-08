import {useCallback, useEffect, useRef, useState} from "react";
import styles from './single-product-preview.module.scss';
import Breadcrumbs from "../product-category-preview/Breadcrumbs";
import Button, {BUTTON_PRIMARY} from "../button/Button";
import Modal from "../modal/Modal";
import ChooseFromGallery from "./ChooseFromGallery";
import Slider from "./Slider";

const SingleProductPreviewComp = ({product}) => {

    const splide = useRef(null);
    const [slideIndex, setSlideIndex] = useState(0);
    const [hasAttributes, setHasAttributes] = useState(false);
    const [attributes, setAttributes] = useState([]);
    const [gallery, setGallery] = useState([]);
    const [isOpenedModal, setIsOpenedModal] = useState(false);
    const [allowCableChoose, setAllowCableChoose] = useState(false);

    useEffect(() => {
        console.log(product);
        let gallery = [];
        if (product.featured_image) {
            gallery.push(product.featured_image[0]);
        }
        if (product.gallery.length > 0) {
            product.gallery.map((image, i) => {
                gallery.push(image);
            });
        }
        if (gallery.length > 0) {
            setGallery(gallery);
        }

        if (product.attributes.length > 1) {
            let count = 0;
            let arr = [];
            for (let i=0; i<product.attributes.length; i++) {
                if (product.attributes[i].attribute_name !== "kabl") {
                    count++;
                    arr.push(product.attributes[i]);
                } else {
                    setAllowCableChoose(true);
                }
            }

            if (count > 0) {
                setHasAttributes(true);
                setAttributes(arr);
            }
        }
    }, []);

    useEffect(() => {
        if (!gallery) {
            return;
        }
        setTimeout(() => {
            splide.current = new Splide('.splide', {
                autoplay: true,
                interval: 3000,
                rewind: true,
                pagination: false
            });
            splide.current.mount();
        }, 150);
    }, [gallery]);

    const slideChanged = useCallback((newIndex, prevIndex, destIndex) => {
        setSlideIndex(newIndex);
    }, []);

    const changeImage = useCallback((index) => {
        splide.current.go(index);
    }, [slideIndex]);

    const goToContact = useCallback((e) => {
        window.showLoader();
        window.reactMain.scrollToTop();
    }, []);

    const openModal = useCallback(() => {
        setIsOpenedModal(true);
    }, []);

    const onClose = useCallback(() => {
        setIsOpenedModal(false);
    }, []);

    const imageSlider = <>
        <Slider
            list={gallery}
            maxHeight={650}
            getImageSource={(item) => item}
            id={"splide-main"}
            hasShadow={true}
            slideChanged={slideChanged}
            thumbnail={'vertical'}
        />
    </>

    return (
        <>
            {product.categories.length > 1 &&
                <Breadcrumbs
                    category={product.categories[1]}
                    subcategory={product.categories[0]}
                />
            }

            {product.categories.length === 1 &&
                <Breadcrumbs
                    category={product.categories[0]}
                />
            }

            <div className={`container ${styles.singleProductContainer}`}>
                <div className={"row"}>
                    <div className={"col-md-6"}>
                        <div className={styles.imageContainer}>

                            {gallery.length > 0 &&
                                <>
                                    <div className={styles.slider}>
                                        {imageSlider}
                                    </div>
                                </>
                            }
                            {gallery.length === 0 && <>
                                <img src={product.featured_image[0]} />
                            </>}

                        </div>
                    </div>
                    <div className={`col-md-6 ${styles.productContent}`}>
                        <h3 className={styles.productTitle}>
                            {product.post_title}
                        </h3>

                        <div className={styles.excerpt}>
                            {product.post_excerpt}
                        </div>

                        {allowCableChoose &&
                            <div className={styles.chooseCable}>
                                <div className={styles.text}>
                                    <div className={styles.title}>
                                        <i className="fa-solid fa-wand-magic-sparkles"></i>
                                        <span className={styles.titleText}>Odaberite boju i tip kabla</span>
                                    </div>
                                    <div className={styles.subtitle}>
                                        Pogledajte asortiman kablova koje nudimo
                                    </div>
                                </div>
                                <div className={styles.button}>
                                    <Button onClick={openModal}>
                                        <i className="fa-solid fa-eye"></i> Odabir
                                    </Button>
                                </div>

                                <Modal isOpen={isOpenedModal} onClose={onClose}>
                                    <ChooseFromGallery variations={product.variations} />
                                </Modal>
                            </div>
                        }

                        <div className={styles.specifications}>
                            <div className={styles.title}>Specifikacije proizvoda</div>
                            <div className={styles.subtitle}>Pogledajte sve detalje vezane za proizvod</div>

                            {!hasAttributes &&
                                <div className={styles.emptyList}>
                                    <div className={styles.icon}>
                                        <i className="fa-solid fa-circle-info fa-2x"></i>
                                    </div>
                                    <div className={styles.text}>Trenutno nema specifikacija za posmatrani proizvod</div>
                                </div>
                            }

                            {hasAttributes &&
                                <div className={styles.list}>
                                    <table>
                                        {attributes.map((row, i) => {
                                            let value = "";
                                            if (row.attribute_values.length === 1) {
                                                value = row.attribute_values[0];
                                            }
                                            return (
                                                <tr key={i}>
                                                    <td className={styles.firstColumn}>{row.attribute_name}:</td>
                                                    <td className={styles.secondColumn}>{value}</td>
                                                </tr>
                                            )
                                        })}
                                    </table>
                                </div>
                            }
                        </div>

                        <div className={styles.buttonPlaceholder}>
                            <a href={"/kontakt"} onClick={goToContact}>
                                <Button theme={BUTTON_PRIMARY} isFull={true}>
                                    <i className="fa-solid fa-phone-volume"></i>
                                    <span className={styles.buttonText}>Kontaktirajte nas</span>
                                </Button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
};

const SingleProductPreview = ({product}) => {

    const [loaded, setLoaded] = useState(false);

    useEffect(() => {
        setLoaded(true);
    }, []);

    if (!loaded) {
        return;
    }

    return <SingleProductPreviewComp product={product} />;
};

export default SingleProductPreview;