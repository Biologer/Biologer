import BaseCroppr from 'croppr'
import Box from 'croppr/src/box'

export default class Croppr extends BaseCroppr {
  setBoxToRealPosition(value) {
    const actualWidth = this.imageEl.naturalWidth
    const actualHeight = this.imageEl.naturalHeight
    const factorX = actualWidth / this.imageEl.offsetWidth
    const factorY = actualHeight / this.imageEl.offsetHeight

    this.box = new Box(
      Math.round(value.x / factorX),
      Math.round(value.y / factorY),
      Math.round((value.x + value.width) / factorX),
      Math.round((value.y + value.height) / factorY)
    )

    this.redraw()
  }
}
