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
            setCategories(react_vars.categories);
            setLoading(false);
        }, 200)
    }, []);

    // useEffect(() => {
    //     const onPopStateHandler=  (e) => {
    //         console.log(e);
    //         if (e.state === null) {
    //             jQuery("#heading-section").parent().fadeIn();
    //         }
    //     };
    //     window.addEventListener('popstate', onPopStateHandler);
    //     return () => {
    //         window.removeEventListener('popstate', onPopStateHandler);
    //     };
    // }, []);

    return (
        <div className={styles.mainContainer}>
            <Loader loading={loading} visible={visible} setVisible={setVisible} />
            {!visible && <Categories categories={categories} />}
        </div>
    );
};

export default App;