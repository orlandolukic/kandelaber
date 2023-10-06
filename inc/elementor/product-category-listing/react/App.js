import styles from './app.module.scss';
import {useEffect, useState} from "react";
import Loader from "./Loader";
import Categories from "./Categories";

const App = () => {

    const [loading, setLoading] = useState(true);
    const [visible, setVisible] = useState(true);
    const [categories, setCategories] = useState(null);

    useEffect(() => {
        history.pushState({
            page: "products-page"
        }, null);

        setTimeout(() => {
            let categories = window.reactMain.categoriesManager.getAllCategories();
            setCategories(categories);
            setLoading(false);
        }, 200)
    }, []);

    return (
        <div className={styles.mainContainer}>
            <Loader loading={loading} visible={visible} setVisible={setVisible} />
            {!visible && <Categories categories={categories} />}
        </div>
    );
};

export default App;