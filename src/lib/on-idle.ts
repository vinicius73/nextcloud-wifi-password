import { isFunction } from "lodash-es";

// @ts-ignore
const subscribe: <R = unknown>(cb: () => R) => R = ((w) => {
  if (isFunction(w.requestIdleCallback)) {
    return w.requestIdleCallback.bind(w);
  }

  if (isFunction(w.requestAnimationFrame)) {
    return w.requestAnimationFrame.bind(w);
  }

  return function _setTimeout(fn: <R>() => R) {
    return setTimeout(fn, 1);
  };
})(window);

const onIdle = <R = unknown>(fn: () => R | Promise<R>): Promise<R> => {
  return new Promise((resolve) => {
    subscribe(() => resolve(fn && fn()));
  });
};

export { onIdle };
