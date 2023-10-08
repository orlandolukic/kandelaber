import styles from './button.module.scss';
import {useCallback} from "react";

export const BUTTON_PRIMARY = "primary";

const Button = ({children, onClick, theme, isFull}) => {

    const onClickHandler = useCallback((e) => {
        if (onClick !== undefined && typeof onClick === 'function') {
            onClick(e);
        }
    }, [onClick]);

    const themeClass = theme !== undefined && theme ? " " + styles[theme] : "";
    const isFullClass = isFull !== undefined && isFull ? " " + styles.isFull : "";

    return (
        <div className={`${styles.buttonPlaceholder}${themeClass}${isFullClass}`} onClick={onClickHandler}>
            {children}
        </div>
    )
};

export default Button;