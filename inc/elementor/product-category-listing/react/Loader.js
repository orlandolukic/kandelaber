import styles from './app.module.scss';
import {useEffect, useState} from "react";

const Loader = ({loading, visible, setVisible}) => {

    useEffect(() => {
        let timeout;
        if (!loading) {
            timeout = setTimeout(() => {
                setVisible(false);
            }, 250);
        } else {
            setVisible(true);
        }
        return () => {
            clearTimeout(timeout);
        };
    }, [loading]);

    return (
        <>
            {visible &&
                <div className={`${styles.loaderContainer}${!loading ? ' ' + styles.loaded : ''}`}>
                    <span className={styles.loader}></span>
                    <div className={styles.loaderText}>Učitavanje</div>
                </div>
            }
        </>
    )
};
export default Loader;