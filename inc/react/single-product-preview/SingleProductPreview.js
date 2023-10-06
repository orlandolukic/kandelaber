import {useEffect, useState} from "react";


const SingleProductPreviewComp = ({product}) => {

    useEffect(() => {

    }, []);

    return (
        <>
            <div className={"container"}>
                <div className={"row"}>
                    <div className={"col-md-6"}>fdsfsdf</div>
                    <div className={"col-md-6"}>sdfsdf</div>
                </div>
            </div>
        </>
    )
};

const SingleProductPreview = ({product}) => {

    const [loaded, setLoaded] = useState(false);

    useEffect(() => {
        setLoaded(true);
    }, []);

    if (!loaded) {
        return;
    }

    return <SingleProductPreviewComp product={product} />;
};

export default SingleProductPreview;