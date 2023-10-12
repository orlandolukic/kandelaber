import {useCallback, useEffect, useRef, useState} from "react";
import styles from './single-product-preview.module.scss';
import Breadcrumbs from "../product-category-preview/Breadcrumbs";
import Button, {BUTTON_PRIMARY} from "../button/Button";
import Modal from "../modal/Modal";
import ChooseFromGallery from "./ChooseFromGallery";
import Slider from "./Slider";
import ProductRecommendations from "../product-recommendations/ProductRecommendations";

const SingleProductPreviewComp = ({product, recommendations, recommended_products_category}) => {

    const [hasAttributes, setHasAttributes] = useState(false);
    const [attributes, setAttributes] = useState([]);
    const [gallery, setGallery] = useState([]);
    const [isOpenedModal, setIsOpenedModal] = useState(false);
    const [allowCableChoose, setAllowCableChoose] = useState(false);
    const [pauseMainSlideshow, setPauseMainSlideshow] = useState(false);

    useEffect(() => {

        // Save product as fetched and visited
        window.productsFactory.visitedProducts.saveVisitedProduct(product.post_name, product, recommendations, recommended_products_category);

        console.log(1212, product);
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

        if (product.attributes.length > 0) {
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

    const goToContact = useCallback((e) => {
        window.showLoader();
        window.reactMain.scrollToTop();
    }, []);

    const openModal = useCallback(() => {
        setIsOpenedModal(true);
        setPauseMainSlideshow(true);
    }, []);

    const onClose = useCallback(() => {
        setIsOpenedModal(false);
        setPauseMainSlideshow(false);
    }, []);

    const imageSlider = <>
        <Slider
            list={gallery}
            maxHeight={650}
            getImageSource={(item) => item}
            id={"splide-main"}
            hasShadow={true}
            thumbnail={'vertical'}
            pauseSlideshow={pauseMainSlideshow}
        />
    </>;

    const mouseEnteredSlider = useCallback(() => {
        setPauseMainSlideshow(true);
    }, []);

    const mouseLeftSlider = useCallback(() => {
        setPauseMainSlideshow(false);
    }, []);

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
                                    <div className={styles.slider} onMouseEnter={mouseEnteredSlider} onMouseLeave={mouseLeftSlider}>
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
                            {product.post_content}
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
                                            } else {
                                                value = row.attribute_values.map((attribute, i) => {
                                                    return (
                                                        <div key={i} className={styles.attributeValue}>{attribute}</div>
                                                    );
                                                });
                                                value = (
                                                    <div className={styles.hasAttributesPlaceholder}>
                                                        <div className={styles.hasAttributes}>
                                                            {value}
                                                        </div>
                                                    </div>
                                                );
                                            }
                                            return (
                                                <tr key={i}>
                                                    <td className={styles.firstColumn}>{row.attribute_name}:</td>
                                                    <td className={`${styles.secondColumn}`}>{value}</td>
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

            <ProductRecommendations product={product} recommendations={recommendations} recommended_products_category={recommended_products_category} />
        </>
    )
};

const SingleProductPreview = ({product, recommended_products, recommended_products_category, slug}) => {

    const [loaded, setLoaded] = useState(false);
    const [productState, setProductState] = useState(product);
    const [recommendationsState, setRecommendationsState] = useState(recommended_products);
    const [recommendedProductsCategoryState, setRecommendedProductsCategoryState] = useState(recommended_products_category);

    useEffect(() => {
        if (slug !== undefined) {
            // We should load product
            console.log("fetching slug");
            window.productsFactory.visitedProducts.getProduct(slug)
                .then((payload) => {
                    setProductState(payload.product);
                    setRecommendationsState(payload.recommended_products);
                    setRecommendedProductsCategoryState(payload.recommended_products_category);

                    history.replaceState({
                        page: 'single-product',
                        product: payload.product,
                        recommended_products: payload.recommended_products,
                        recommended_products_category: payload.recommended_products_category
                    }, null);

                    setLoaded(true);
                    setTimeout(() => {
                        window.hideLoader();
                    }, 100);
                })
        } else {
            setLoaded(true);

            history.replaceState({
                page: 'single-product',
                product: product,
                recommended_products: recommended_products,
                recommended_products_category: recommended_products_category
            }, null);

            setTimeout(() => {
                window.hideLoader();
            }, 100);
        }
    }, []);

    if (!loaded) {
        return;
    }

    return <SingleProductPreviewComp product={productState} recommendations={recommendationsState} recommended_products_category={recommendedProductsCategoryState} />;
};

export default SingleProductPreview;