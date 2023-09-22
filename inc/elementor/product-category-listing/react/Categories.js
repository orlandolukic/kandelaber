import styles from './app.module.scss';
import SubcategoriesOverlay from "./SubcategoriesOverlay";
import {useState} from "react";
import SingleCategory from "./SingleCategory";

const Categories = ({categories}) => {

    const categoriesElements = categories.map((category, i) => (
        <SingleCategory category={category} key={category.term_id} />
    ));

    return (
        <div className={`row ${styles.categoriesPlaceholder}`}>
            {categoriesElements}
        </div>
    )
};

export default Categories;