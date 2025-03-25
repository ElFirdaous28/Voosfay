import React, { useState } from "react";

function Counter() {
    const [count, setCount] = useState(0);

    return (
        <div className="p-5 text-center">
            <h1 className="text-2xl font-bold">Counter: {count}</h1>
            <button
                onClick={() => setCount(count + 1)}
                className="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg"
            >
                Increment
            </button>
            <button
                onClick={() => setCount(count - 1)}
                className="mt-4 ml-2 px-4 py-2 bg-red-500 text-white rounded-lg"
            >
                Decrement
            </button>
        </div>
    );
}

export default Counter;
