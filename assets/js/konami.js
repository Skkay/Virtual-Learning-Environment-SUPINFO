import confetti from 'canvas-confetti';

const konami = (callback) => {
    let kkeys = [];
    const keyCode = '38,38,40,40,37,39,37,39,66,65';
    return (event) => {
        kkeys.push(event.keyCode);
        if (kkeys.toString().indexOf(keyCode) >= 0) {
            callback(event);
            kkeys = [];
        }
    };
};

const confettiCanvas = document.createElement('canvas');
confettiCanvas.style.position = 'fixed';
confettiCanvas.style.top = '0px';
confettiCanvas.style.left = '0px';
confettiCanvas.style.width = '100%';
confettiCanvas.style.height = '100%';
confettiCanvas.style.pointerEvents = 'none';
document.body.appendChild(confettiCanvas);

const myConfetti = confetti.create(confettiCanvas, {
    resize: true,
    useWorker: true,
});

window.addEventListener('keydown', konami(() => {
    myConfetti({
        particleCount: 200,
        spread: 200,
    });
}));
