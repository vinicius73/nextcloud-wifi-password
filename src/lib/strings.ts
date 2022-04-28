export const random = (size = 10) =>
  Math.random()
    .toString(36)
    .replace(/[^a-z]+/g, "")
    .substring(0, size);
