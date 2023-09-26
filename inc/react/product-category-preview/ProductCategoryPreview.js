
import styles from './product-category-preview.module.scss';
import {useCallback, useEffect} from "react";
import Breadcrumbs from "./Breadcrumbs";

const ProductCategoryPreview = ({category, subcategory}) => {

    return (
        <>
            <div className={` ${styles.productCategoryPreviewContainer}`}>

                <Breadcrumbs category={category} subcategory={subcategory} />

                <div className={`container ${styles.categoryListing}`}>
                    <div className={"row"}>
                        <div className={"col-md-12"}>
                            Test
                        </div>
                    </div>
                </div>

            </div>
        </>
    );
};

export default ProductCategoryPreview;