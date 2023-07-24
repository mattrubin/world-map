import map from './map.svg';

export interface Env {}

export default {
  async fetch(request: Request, env: Env, ctx: ExecutionContext): Promise<Response> {
    const params = new URL(request.url).searchParams;

    const color1 = params.get('color1') ?? '660099';
    const color2 = params.get('color2') ?? '0000FF';
    const color3 = params.get('color3') ?? '00FF00';
    const color4 = params.get('color4') ?? 'FFFF00';
    const color5 = params.get('color5') ?? 'FF0000';

    const cutoff1 = Number(params.get('cutoff1') ?? '2');
    const cutoff2 = Number(params.get('cutoff2') ?? '5');
    const cutoff3 = Number(params.get('cutoff3') ?? '10');
    const cutoff4 = Number(params.get('cutoff4') ?? '20');

    const colors = new Map<string, string>();
    for (const [rawKey, rawValue] of params) {
      const key = rawKey.toLowerCase();
      const value = Number(rawValue);
      if (key.length == 2) {
        if (value >= cutoff4) {
          colors.set(key, color5);
        } else if (value >= cutoff3) {
          colors.set(key, color4);
        } else if (value >= cutoff2) {
          colors.set(key, color3);
        } else if (value >= cutoff1) {
          colors.set(key, color2);
        } else if (value > 0) {
          colors.set(key, color1);
        }
      }
    }

    let styleString = '.oceanxx { fill: #B5D6FE; }\n.landxx, .coastxx, .antxx { stroke: #000; stroke-width:0.3; }\n';
    for (const [region, color] of colors) {
      styleString += `.${region} { fill: #${color}; }\n`;
    }

    const mapWithStyle = map.replace('/** STYLE **/', styleString);

    const response = new Response(mapWithStyle);
    response.headers.set('Content-type', 'image/svg+xml');
    return response;
  },
};
