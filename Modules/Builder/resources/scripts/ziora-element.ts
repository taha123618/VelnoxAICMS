import { getId } from '@/helpers';
import {
    BackgroundType,
    CurrentState,
    DeviceType,
    InputTypes,
} from '@modules/Builder/resources/scripts/enums';
import { getNestedProperty } from '@modules/Builder/resources/scripts/factory';
import { TElement } from '@modules/Builder/resources/scripts/types';
import {
    deepCopy,
    isEmpty,
    objectIsEqual,
} from '@modules/Builder/resources/scripts/utils';
import { Case } from 'change-case-all';

export default class ZioraElement {
    id: string;
    type: string;
    canDrop: boolean;
    isLayoutElement: boolean;
    name: string;
    icon: string;
    children: ZioraElement[];
    props: Record<string, any>;

    constructor(args: TElement) {
        this.id = args.id;
        this.canDrop = args.canDrop;
        this.isLayoutElement = args.isLayoutElement;
        this.type = args.type;
        this.name = args.name;
        this.icon = args.icon;
        this.children = args.children;
        this.props = deepCopy(args.props);
    }

    setName(name: string) {
        this.name = name;
    }

    appendContentElements(injectable: ZioraElement[]) {
        this.children = injectable;
    }

    addChild(element: ZioraElement) {
        if (Array.isArray(this.children)) {
            this.children.push(element);
        }
    }

    removeChild(index: number) {
        if (Array.isArray(this.children)) {
            this.children.splice(index, 1);
        }
    }

    hasContent(): boolean {
        return Array.isArray(this.children) && this.children.length > 0;
    }

    hasChildren(): boolean {
        return Array.isArray(this.children) && this.children.length > 0;
    }

    isRootElement(): boolean {
        return this.type == 'body' || this.type == 'wrapper';
    }

    setContent(key: string, content: string | null = null): void {
        this.props.content = { ...this.props.content, [key]: content };
    }

    getContent(key: string): undefined | number | string {
        if (!this.props.content || this.props.content == undefined) {
            this.setContent(key, '');
        }

        return this.props.content[key];
    }

    getBackgroundStyles(deviceName: DeviceType, currentState: CurrentState) {
        if (this.props.styles[deviceName][currentState] == undefined) {
            return {};
        }

        const bg = this.props.styles[deviceName][currentState].background;

        const styles: Record<string, any> = {};

        if (bg == undefined) {
            return {};
        }

        if (bg.type == undefined) {
            bg.type = BackgroundType.Classic;
        }

        if (bg.type == BackgroundType.Classic) {
            styles['background-color'] = bg.color;
            if (!!bg.imageSource) {
                styles['background-image'] = `url('${bg.imageSource}')`;
            }
        } else if (bg.type == BackgroundType.Gradient) {
            styles['background-image'] = bg.gradientBackgroundImage;
            if (!!bg.imageSource) {
                styles['background-image'] += `, url('${bg.imageSource}')`;
            }
        }

        if (!!bg.imageSource) {
            styles['background-attachment'] = bg.imageAttachment;
            styles['background-position'] = bg.imagePosition;
            styles['background-repeat'] = bg.imageRepeat;
            styles['background-size'] = bg.imageSize;
            styles['background-blend-mode'] = bg.imageBlendMode;
        }

        return styles;
    }

    getDeviceStyles(
        deviceName: DeviceType,
        currentState: CurrentState,
    ): string {
        const fallbackStyles: Record<string, any> =
            this.props.styles[DeviceType.Desktop]?.[currentState] || {};
        const deviceStyles: Record<string, any> =
            this.props.styles[deviceName]?.[currentState] || {};

        let styles: Record<string, any> = {};

        const styleResolverMap: Record<string, any> = {
            boxShadow: ({
                x,
                y,
                blur,
                spread,
                color,
            }: {
                x: number;
                y: number;
                blur: number;
                spread: number;
                color: string;
            }) => `${x}px ${y}px ${blur}px ${spread}px ${color}`,

            backgroundImage: (url: string) =>
                url !== 'none' ? `url('${url}')` : url,

            textShadow: ({
                x,
                y,
                blur,
                color,
            }: {
                x: number;
                y: number;
                blur: number;
                color: string;
            }) => `${x}px ${y}px ${blur}px ${color}`,
        };

        Object.entries(deviceStyles).forEach(([key, value]) => {
            if (key == 'background') {
                return;
            }

            const casedKey: string = Case.kebab(key);

            if (styleResolverMap[key]) {
                styles[casedKey] = `${styleResolverMap[key](value)}`;
            } else if (key.startsWith('webkit')) {
                const newKey = `-${casedKey}`;
                styles[newKey] =
                    typeof value == 'number' ? `${value}px` : value;
            } else if (key.startsWith('grid')) {
                if (typeof value === 'object') {
                    styles[casedKey] =
                        value.unit == 'custom'
                            ? value.value
                            : `repeat(${value.value}, minmax(0, 1fr))`;
                } else if (key == 'gridColumn' || key == 'gridRow') {
                    const val = value == 0 ? 1 : value;
                    styles[casedKey] = `span ${val} / span ${val}`;
                } else {
                    styles[casedKey] = `${value}`;
                }
            } else if (typeof value === 'object' && value.isFourWay == true) {
                if (value.unit == undefined) {
                    value.unit = fallbackStyles[key].unit;
                }
                if (value.value == undefined) {
                    value.value = fallbackStyles[key].value;
                }

                if (value.unit == 'custom' || !value.hasUnit) {
                    styles[casedKey] =
                        `${value.top} ${value.right} ${value.bottom} ${value.left}`;
                } else {
                    styles[casedKey] =
                        `${value.top}${value.unit} ${value.right}${value.unit} ${value.bottom}${value.unit} ${value.left}${value.unit}`;
                }
            } else if (typeof value === 'object') {
                if (value.unit == undefined) {
                    value.unit = fallbackStyles[key].unit;
                }
                if (value.value == undefined) {
                    value.value = fallbackStyles[key].value;
                }

                if (value.unit == 'auto') {
                    styles[casedKey] = 'auto';
                } else if (value.unit == 'custom') {
                    styles[casedKey] = value.value;
                } else {
                    styles[casedKey] = `${value.value}${value.unit}`;
                }
            } else if (typeof value === 'number') {
                styles[casedKey] = `${value}px`;
            } else {
                styles[casedKey] = `${value}`;
            }
        });

        const bgStyles = this.getBackgroundStyles(deviceName, currentState);

        styles = { ...styles, ...bgStyles };

        const styleString: string = Object.entries(styles)
            .map(([k, v]) => `${k}:${v}`)
            .join(';');

        return styleString;
    }

    getElementStylesForRender() {
        return {
            [DeviceType.Desktop]: {
                [CurrentState.Default]: this.getDeviceStyles(
                    DeviceType.Desktop,
                    CurrentState.Default,
                ),
                [CurrentState.Hover]: this.getDeviceStyles(
                    DeviceType.Desktop,
                    CurrentState.Hover,
                ),
            },
            [DeviceType.Tablet]: {
                [CurrentState.Default]: this.getDeviceStyles(
                    DeviceType.Tablet,
                    CurrentState.Default,
                ),
                [CurrentState.Hover]: this.getDeviceStyles(
                    DeviceType.Tablet,
                    CurrentState.Hover,
                ),
            },
            [DeviceType.Mobile]: {
                [CurrentState.Default]: this.getDeviceStyles(
                    DeviceType.Mobile,
                    CurrentState.Default,
                ),
                [CurrentState.Hover]: this.getDeviceStyles(
                    DeviceType.Mobile,
                    CurrentState.Hover,
                ),
            },
        };
    }

    hasChanged(device: DeviceType, property: string) {
        const defaultStyle = this.getStyle(
            device,
            CurrentState.Default,
            property,
        );
        const hoverStyle = this.getStateStyle(
            device,
            CurrentState.Hover,
            property,
        );
        if (!hoverStyle || hoverStyle == undefined) return false;

        if (typeof hoverStyle == 'object') {
            return !objectIsEqual(hoverStyle, defaultStyle);
        }
        return defaultStyle != hoverStyle;
    }

    getStateStyle(device: DeviceType, state: CurrentState, property: string) {
        const deviceStyles = this.props.styles[device]?.[state];
        return getNestedProperty(deviceStyles, property) ?? null;
    }

    getEmptyGridCellCount(deviceName: DeviceType, state: CurrentState) {
        if (this.type !== 'grid' && this.type !== 'form') {
            return 0;
        }

        const cols = this.getStyle(
            deviceName,
            state,
            'gridTemplateColumns.value',
        );
        const rows = this.getStyle(deviceName, state, 'gridTemplateRows.value');

        const cnt = Number(cols) * Number(rows) - this.children.length;

        return Math.max(0, cnt);
    }

    getStyle(device: DeviceType, state: CurrentState, property: string) {
        const fallbackDevice = DeviceType.Desktop;
        const fallbackState = CurrentState.Default;

        const deviceStyles =
            this.props.styles[device]?.[state] ??
            this.props.styles[fallbackDevice][state] ??
            this.props.styles[fallbackDevice][fallbackState];

        return (
            getNestedProperty(deviceStyles, property) ??
            getNestedProperty(
                this.props.styles[fallbackDevice][state],
                property,
            ) ??
            getNestedProperty(
                this.props.styles[fallbackDevice][fallbackState],
                property,
            ) ??
            null
        );
    }

    setStyle(
        device: DeviceType,
        state: CurrentState,
        property: string,
        value: any,
    ) {
        if (!this.props.styles[device]) {
            this.props.styles[device] = {
                default: {},
                hover: {},
                active: {},
            };
        }
        if (
            !this.props.styles[device][state] ||
            isEmpty(this.props.styles[device][state])
        ) {
            this.props.styles[device][state] = {};
        }

        const keys = property.split('.');

        let target = this.props.styles[device][state];

        for (let i = 0; i < keys.length - 1; i++) {
            if (!target[keys[i]!]) {
                target[keys[i]!] = {};
            }
            target = target[keys[i]!];
        }

        target[keys[keys.length - 1]!] = value;
    }

    deleteHoverStyle(device: DeviceType, property: string) {
        const keys = property.split('.');
        let target = this.props.styles[device][CurrentState.Hover];

        for (let i = 0; i < keys.length - 1; i++) {
            if (!target[keys[i]!]) {
                return false;
            }
            target = target[keys[i]!];
        }
        return delete target[keys[keys.length - 1]!];
    }

    hasStyle(property: string) {
        const fallbackDevice = DeviceType.Desktop;
        const fallbackState = CurrentState.Default;

        const keys = property.split('.');
        let target = this.props.styles[fallbackDevice]?.[fallbackState];

        for (const key of keys) {
            if (!target || !Object.prototype.hasOwnProperty.call(target, key)) {
                return false;
            }
            target = target[key];
        }
        return true;
    }

    hasProp(property: string) {
        const keys = property.split('.');
        let target = this.props;
        for (const key of keys) {
            if (!target || !Object.prototype.hasOwnProperty.call(target, key)) {
                return false;
            }
            target = target[key];
        }
        return true;
    }

    getProp(property: string): any {
        return getNestedProperty(this.props, property) ?? null;
    }

    setProps(property: string, value: any) {
        if (value == undefined) return;
        const keys = property.split('.');
        let target = this.props;

        for (let i = 0; i < keys.length - 1; i++) {
            if (!target[keys[i]!]) {
                target[keys[i]!] = {};
            }
            target = target[keys[i]!];
        }

        target[keys[keys.length - 1]!] = value;
    }

    hasCustomStyle(property: string) {
        const keys = property.split('.');
        let target = this.props.styles.custom;
        for (const key of keys) {
            if (!target || !Object.prototype.hasOwnProperty.call(target, key)) {
                return false;
            }
            target = target[key];
        }
        return true;
    }

    setCustomStyle(property: string, value: any) {
        if (value == undefined) return;

        const keys = property.split('.');
        let target = this.props.styles.custom;

        for (let i = 0; i < keys.length - 1; i++) {
            if (!target[keys[i]!]) {
                target[keys[i]!] = {};
            }
            target = target[keys[i]!];
        }

        target[keys[keys.length - 1]!] = value;
    }

    getCustomStyle(property: string): any {
        return getNestedProperty(this.props.styles.custom, property) ?? '';
    }

    getFormFields() {
        if (this.type !== 'form') {
            return [];
        }
        const fields: Record<string, any> = {};

        this.children.forEach((child) => {
            if (child.type !== 'forminput') {
                return;
            }
            fields[child.getProp('name')] = child.getFormElementInitialValue();
        });

        return fields;
    }
    
    getFormElementInitialValue() {
        if (this.type !== 'forminput') {
            return;
        }
        switch (this.getProp('type')) {
            case InputTypes.MultiSelect:
            case InputTypes.CheckboxGroup:
                return [];
            case InputTypes.Date:
                return '';
            case InputTypes.Number:
                return 0;
            case InputTypes.Checkbox:
                return false;
            case InputTypes.Email:
            case InputTypes.Password:
            case InputTypes.Radio:
            case InputTypes.Text:
            case InputTypes.Select:
            case InputTypes.Textarea:
                return '';
            default:
                return '';
        }
    }

    static newFromObject(obj: TElement): any {
        if (!obj || typeof obj !== 'object') return obj;

        return new ZioraElement({
            ...obj,
            id: getId(),
            children: Array.isArray(obj.children)
                ? obj.children.map(
                      ZioraElement.newFromObject.bind(ZioraElement),
                  )
                : obj.children,
        });
    }

    static updateIsLayoutProperty(
        obj: TElement,
        isLayoutElement: boolean,
    ): TElement {
        obj.isLayoutElement = isLayoutElement;

        if (Array.isArray(obj.children)) {
            for (const child of obj.children) {
                this.updateIsLayoutProperty(child, isLayoutElement);
            }
        }

        return obj;
    }

    static fromObject(obj: TElement): any {
        if (!obj || typeof obj !== 'object') return obj;

        return new ZioraElement({
            ...obj,
            children: Array.isArray(obj.children)
                ? obj.children.map(ZioraElement.fromObject.bind(ZioraElement))
                : obj.children,
        });
    }

    toJSON() {
        return { ...this };
    }
}
