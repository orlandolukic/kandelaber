import styles from './test.module.scss';

const App = () => {

    const clickHandler = () => {
        console.log("sdsdsd");
    }
    return (
        <div>
            <h2 onClick={clickHandler}>Hello World</h2>
        </div>
    );
};

export default App;