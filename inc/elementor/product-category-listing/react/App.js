import styles from './app.module.scss';
import {useState} from "react";
import Loader from "./Loader";

const App = () => {

    const [loading, setLoading] = useState(true);
    const [response, setResponse] = useState(null);
    const clickHandler = () => {
        jQuery.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'custom_ajax_action',
                parameter1: 'value1',
                parameter2: 'value2'
            },
            success: function(response) {
                // Handle the response from the server
                setResponse(response);
            }
        });
    }


    return (
        <div className={styles.mainContainer}>
            {loading &&
                <Loader />
            }
        </div>
    );
};

export default App;