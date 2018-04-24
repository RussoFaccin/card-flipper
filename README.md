# Card Flipper
Card Flipper Wordpress Plugin
### Styling
Add to css theme file.
```sh
#cardflipper-container {
	display: flex;
}

.card-item {
	width: 200px;
  height: 260px;
  position: relative;
	perspective: 800px;
}

.card-item:hover .card-wraper {
	transform: rotateY(180deg);
}

.card-wraper {
	width: 100%;
  height: 100%;
  position: absolute;
  transform-style: preserve-3d;
  transition: transform 1s;
}

.card-wraper .card-face {
	margin: 0;
  display: block;
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
}

.card-wraper .card-face:nth-child(1) {
	transform: rotateY(180deg);
	z-index: 2;
}

.card-wraper .card-face:nth-child(2) {
	z-index: 1;
}

.card-face img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}
```