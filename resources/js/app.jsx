import React from "react";
import ReactDOM from "react-dom/client";
import Counter from "./components/Counter";

function App() {
    return (
        <div className="flex justify-center items-center h-screen bg-gray-100">
            <Counter />
        </div>
    );
}

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
