import styles from "./single-product-preview.module.scss";
import Button from "../button/Button";
import Modal from "../modal/Modal";
import ChooseFromGallery from "./ChooseFromGallery";

const FastCollections = ({fastCollections, openModal, isOpenedModal, onClose}) => {
    return fastCollections.map((collection, i) => {
        return (
            <div key={i} className={styles.fastCollectionPlaceholder}>
                <div className={styles.text}>
                    <div className={styles.title}>
                        <i className={collection.metadata.title_icon}></i>
                        <span className={styles.titleText}>{collection.metadata.title}</span>
                    </div>
                    <div className={styles.subtitle}>
                        {collection.metadata.subtitle}
                    </div>
                </div>
                <div className={styles.button}>
                    <Button onClick={openModal}>
                        <i className={collection.metadata.button_icon}></i> {collection.metadata.button_text}
                    </Button>
                </div>

                <Modal isOpen={isOpenedModal} onClose={onClose}>
                    <ChooseFromGallery variations={collection.variations} metadata={collection.metadata} />
                </Modal>
            </div>
        )
    });
}

export default FastCollections;