import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import axios from 'axios';

export const usePostList = (element: ZioraElement) => {
    const { className } = useElement(element);

    const posts = ref<Modules.Page.Data.PostData[]>([]);
    

    function fetchPosts() {
        axios
            .get(
                route('api.posts.index', {
                    perPage: element.getProp('query.perPage'),
                    orderBy: element.getProp('query.orderBy'),
                    orderDir: element.getProp('query.orderDir'),
                    fromCategories: element.getProp('query.fromCategories'),
                    categories: element.getProp('query.categories'),
                }),
            )
            .then((response) => {
                posts.value = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    }

    watchEffect(() => {
        fetchPosts();
    });

    function getStyles() {
        return `
            .${className.value} {
                .container {
                    display: grid;
                    grid-template-columns: repeat(${element.getProp('container.columns')}, minmax(0, 1fr));
                    row-gap: ${element.getProp('container.rowGap')}px;
                    column-gap: ${element.getProp('container.columnGap')}px;
                }
                .metabox {
                    padding: ${element.getProp('metabox.padding')}px;
                }    
                .item {
                    border-radius: ${element.getProp('item.borderRadius')}px;
                    border-style: ${element.getProp('item.borderStyle')};
                    border-width: ${element.getProp('item.borderWidth')}px;
                    border-color: ${element.getProp('item.borderColor')};
                    background-color: ${element.getProp('item.backgroundColor')};
                    overflow: hidden;
                }
                .img{
                    height: ${element.getProp('img.height.unit') == 'auto' ? 'auto' : element.getProp('img.height.value') + element.getProp('img.height.unit')};
                    width: ${element.getProp('img.width.unit') == 'auto' ? 'auto' : element.getProp('img.width.value') + element.getProp('img.width.unit')};
                }

                .title {
                    font-family: ${element.getProp('title.fontFamily')};
                    font-size: ${element.getProp('title.fontSize')}px;
                    font-weight: ${element.getProp('title.fontWeight')};
                    color: ${element.getProp('title.color')};
                }
                .excerpt {
                    font-family: ${element.getProp('excerpt.fontFamily')};
                    font-size: ${element.getProp('excerpt.fontSize')}px;
                    font-weight: ${element.getProp('excerpt.fontWeight')};
                    color: ${element.getProp('excerpt.color')};
                }
                .link {
                    border-radius: ${element.getProp('link.borderRadius')}px;
                    font-family: ${element.getProp('link.fontFamily')};
                    font-size: ${element.getProp('link.fontSize')}px;
                    font-weight: ${element.getProp('link.fontWeight')};
                    color: ${element.getProp('link.color')};
                    background-color: ${element.getProp('link.backgroundColor')};
                    padding-top: ${element.getProp('link.padding.top') + element.getProp('link.padding.unit')};
                    padding-right: ${element.getProp('link.padding.right') + element.getProp('link.padding.unit')};
                    padding-bottom: ${element.getProp('link.padding.bottom') + element.getProp('link.padding.unit')};
                    padding-left: ${element.getProp('link.padding.left') + element.getProp('link.padding.unit')};
                }
            }

            @container(max-width: 576px) {
                .${className.value} {
                    .container {
                        grid-template-columns: repeat(1, minmax(0, 1fr));
                        row-gap: ${element.getProp('container.rowGap')};
                        column-gap: ${element.getProp('container.columnGap')};
                    }
                }
            }
        `;
    }

    return {
        posts,
        // categories,
        getStyles,
        fetchPosts,
    };
};
