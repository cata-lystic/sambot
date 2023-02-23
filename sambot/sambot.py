from redbot.core import commands
import aiohttp


class Samaritan(commands.Cog):
    """Random quote or rekoning from Samaritan"""

    async def red_delete_data_for_user(self, **kwargs):
        """ Nothing to delete """
        return

    def __init__(self, bot):
        self.bot = bot

    @commands.command()
    async def sam(self, ctx):
        """Shows the avatar emoji."""
        await ctx.send(f"<:samaritan:1078116149029519491>")

    @commands.command()
    async def rekon(self, ctx, choice=""):
        """Gets a random Sam rekon."""
        try:
            async with aiohttp.request("GET", "https://sambot.frwd.app?q="+choice+"&platform=discord", headers={"Accept": "text/plain"}) as r:
                if r.status != 200:
                    return await ctx.send("Oops! Cannot get a Sam rekon...")
                result = await r.text(encoding="UTF-8")
        except aiohttp.ClientConnectionError:
            return await ctx.send("Oops! Cannot get a Sam rekon...")

        await ctx.send(f"<:samaritan:1078116149029519491> `{result}`")