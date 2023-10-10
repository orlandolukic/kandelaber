import './modal.scss';
import {useCallback, useEffect} from "react";

const Modal = ({ isOpen, onClose, children, onOpen }) => {
    if (!isOpen) return null;

    const onCloseHandler = useCallback((e) => {
        if (e.target.className === 'modal-overlay') {
            onClose();
        }
    }, []);

    useEffect(() => {
        let handler = null;
        if (isOpen) {
            handler = (e) => {
                if (e.key === "Escape") {
                    onClose();
                }
            };

            tippy('.close-button', {
                content: "Zatvorite prozor",
                offset: [0, 20],
                placement: 'right'
            });
        }

        if (handler !== null) {
            window.addEventListener('keyup', handler);
        }
        return () => {
            window.removeEventListener('keyup', handler);
        };

        if (typeof onOpen === 'function') {
            onOpen();
        }
    }, [isOpen]);

    return ReactDOM.createPortal(
        <div className="modal-overlay" onClick={onCloseHandler}>
            <div className="modal">
                <div onClick={onClose} className={`modal-close-button close-button`}>
                    <i className="fa-solid fa-xmark fa-2x"></i>
                </div>
                {children}
            </div>
        </div>,
        document.body
    );
};

export default Modal;