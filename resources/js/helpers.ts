import { customAlphabet } from 'nanoid';
const nanoid = customAlphabet('1234567890abcdefghijklmnopqrstuvwxyz', 10);

export function pickBy(object: Record<string, any>, omit: boolean = false) {
    const obj: Record<string, any> = {};
    for (const key in object) {
        if (omit && !object[key]) {
            continue;
        }
        obj[key] = object[key];
        // if (object[key]) {
        //     obj[key] = object[key];
        // }
    }
    return obj;
}

export function getId() {
    return nanoid();
}
