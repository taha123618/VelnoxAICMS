export const useTiptap = () => {
    function processHtmlContent(htmlContent: string) {
        let html: string =
            htmlContent.match(/<\/?p[^>]*>/g)?.length == 2
                ? htmlContent.replace(/<\/?p[^>]*>/g, '')
                : htmlContent;

        html = html.replace(
            /(<ul[^>]*>)(.*?)(<\/ul>)||(<li[^>]*>)(.*?)(<\/li>)/g,
            (res) => {
                return res
                    ? res.replace(
                          /(<li[^>]*>)(.*?)(<\/li[^>]*>)/g,
                          (response) => {
                              return response.match(/<\/?p[^>]*>/g)?.length == 2
                                  ? response.replace(/<\/?p[^>]*>/g, '')
                                  : '';
                          },
                      )
                    : '';
            },
        );

        return html;
    }

    return {
        processHtmlContent,
    };
};
