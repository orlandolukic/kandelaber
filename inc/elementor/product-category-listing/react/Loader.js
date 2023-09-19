import styles from './app.module.scss';
const Loader = () => {
    return (
        <div className={styles.loaderContainer}>
            <span className={styles.loader}></span>
            <div className={styles.loaderText}>Učitavanje</div>
        </div>

    )
};
export default Loader;