import styles from './button.module.scss';
import {useCallback} from "react";

const Button = ({children, onClick}) => {

    const onClickHandler = useCallback((e) => {
        if (onClick !== undefined && typeof onClick === 'function') {
            onClick(e);
        }
    }, [onClick]);

    return (
        <div className={styles.buttonPlaceholder} onClick={onClickHandler}>
            {children}
        </div>
    )
};

export default Button;